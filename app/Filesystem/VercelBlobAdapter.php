<?php

namespace App\Filesystem;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\UnableToCheckExistence;
use League\Flysystem\UnableToCopyFile;
use League\Flysystem\UnableToCreateDirectory;
use League\Flysystem\UnableToDeleteDirectory;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToMoveFile;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToRetrieveMetadata;
use League\Flysystem\UnableToSetVisibility;
use League\Flysystem\UnableToWriteFile;
use Symfony\Component\Mime\MimeTypes;

/**
 * Stores public uploads in Vercel Blob so they survive restarts and deployments.
 */
class VercelBlobAdapter implements FilesystemAdapter
{
    private const API_URL = 'https://vercel.com/api/blob';

    private const API_VERSION = '13';

    public function __construct(private string $token) {}

    /**
     * Get the public URL of a stored file.
     */
    public function getUrl(string $path): string
    {
        return "https://{$this->storeId()}.public.blob.vercel-storage.com/".ltrim($path, '/');
    }

    public function write(string $path, string $contents, Config $config): void
    {
        $response = $this->request()
            ->withHeaders([
                'x-vercel-blob-access' => 'public',
                'x-allow-overwrite' => '1',
                'x-content-type' => $this->contentType($path),
                'x-content-length' => (string) strlen($contents),
            ])
            ->withBody($contents, 'application/octet-stream')
            ->put(self::API_URL.'/?'.http_build_query(['pathname' => $path]));

        if ($response->failed()) {
            throw UnableToWriteFile::atLocation($path, "Vercel Blob responded with {$response->status()}.");
        }
    }

    /**
     * @param  resource  $contents
     */
    public function writeStream(string $path, $contents, Config $config): void
    {
        $this->write($path, (string) stream_get_contents($contents), $config);
    }

    public function delete(string $path): void
    {
        $response = $this->request()->post(self::API_URL.'/delete', ['urls' => [$this->getUrl($path)]]);

        if ($response->failed()) {
            throw UnableToDeleteFile::atLocation($path, "Vercel Blob responded with {$response->status()}.");
        }
    }

    public function fileExists(string $path): bool
    {
        return Http::head($this->getUrl($path))->successful();
    }

    public function read(string $path): string
    {
        $response = Http::get($this->getUrl($path));

        if ($response->failed()) {
            throw UnableToReadFile::fromLocation($path, "Vercel Blob responded with {$response->status()}.");
        }

        return $response->body();
    }

    /**
     * @return resource
     */
    public function readStream(string $path)
    {
        $stream = fopen('php://temp', 'r+b');

        fwrite($stream, $this->read($path));
        rewind($stream);

        return $stream;
    }

    public function directoryExists(string $path): bool
    {
        throw UnableToCheckExistence::forLocation($path);
    }

    public function deleteDirectory(string $path): void
    {
        throw UnableToDeleteDirectory::atLocation($path, 'Vercel Blob has no directories.');
    }

    public function createDirectory(string $path, Config $config): void
    {
        throw UnableToCreateDirectory::atLocation($path, 'Vercel Blob has no directories.');
    }

    public function setVisibility(string $path, string $visibility): void
    {
        throw UnableToSetVisibility::atLocation($path, 'Every file in this store is public.');
    }

    public function visibility(string $path): FileAttributes
    {
        return new FileAttributes($path, null, 'public');
    }

    public function mimeType(string $path): FileAttributes
    {
        return new FileAttributes($path, mimeType: $this->contentType($path));
    }

    public function lastModified(string $path): FileAttributes
    {
        throw UnableToRetrieveMetadata::lastModified($path);
    }

    public function fileSize(string $path): FileAttributes
    {
        throw UnableToRetrieveMetadata::fileSize($path);
    }

    /**
     * @return iterable<int, FileAttributes>
     */
    public function listContents(string $path, bool $deep): iterable
    {
        return [];
    }

    public function move(string $source, string $destination, Config $config): void
    {
        throw UnableToMoveFile::fromLocationTo($source, $destination);
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        throw UnableToCopyFile::fromLocationTo($source, $destination);
    }

    private function request(): PendingRequest
    {
        return Http::withToken($this->token)
            ->withHeaders([
                'x-api-version' => self::API_VERSION,
                'x-vercel-blob-store-id' => $this->storeId(),
            ])
            ->timeout(30);
    }

    /**
     * Read the store ID from a token shaped like vercel_blob_rw_{storeId}_{secret}.
     */
    private function storeId(): string
    {
        return strtolower(explode('_', $this->token)[3] ?? '');
    }

    private function contentType(string $path): string
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return (new MimeTypes)->getMimeTypes($extension)[0] ?? 'application/octet-stream';
    }
}
