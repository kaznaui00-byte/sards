<?php

use App\Filesystem\VercelBlobAdapter;
use App\Support\Media;
use Illuminate\Http\Client\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Config;
use League\Flysystem\UnableToWriteFile;

beforeEach(function () {
    config([
        'filesystems.disks.public.driver' => 'vercel-blob',
        'filesystems.disks.public.token' => 'vercel_blob_rw_StoreId123_secret',
    ]);

    Storage::forgetDisk('public');
});

it('uploads files to the blob store with the official request format', function () {
    Http::fake(['vercel.com/api/blob/*' => Http::response(['url' => 'https://example.test/file'])]);

    $path = UploadedFile::fake()->create('episode.mp3', 10, 'audio/mpeg')->store('quote-audios', 'public');

    Http::assertSent(function (Request $request) use ($path) {
        return $request->method() === 'PUT'
            && str_contains($request->url(), 'pathname='.urlencode($path))
            && $request->hasHeader('Authorization', 'Bearer vercel_blob_rw_StoreId123_secret')
            && $request->hasHeader('x-vercel-blob-access', 'public')
            && $request->hasHeader('x-api-version', '13');
    });
});

it('builds public file links from the store', function () {
    expect(Media::url('avatars/me.jpg'))
        ->toBe('https://storeid123.public.blob.vercel-storage.com/avatars/me.jpg');
});

it('deletes files from the blob store', function () {
    Http::fake(['vercel.com/api/blob/delete' => Http::response([])]);

    Storage::disk('public')->delete('avatars/me.jpg');

    Http::assertSent(fn (Request $request) => $request['urls'] === ['https://storeid123.public.blob.vercel-storage.com/avatars/me.jpg']);
});

it('reports failed uploads instead of hiding them', function () {
    Http::fake(['vercel.com/api/blob/*' => Http::response([], 500)]);

    (new VercelBlobAdapter('vercel_blob_rw_StoreId123_secret'))->write('a.mp3', 'data', new Config);
})->throws(UnableToWriteFile::class);

it('returns an empty link for a missing file', function () {
    expect(Media::url(null))->toBe('');
});
