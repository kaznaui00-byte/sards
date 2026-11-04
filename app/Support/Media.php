<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class Media
{
    /**
     * Get the public URL of an uploaded file on the active storage.
     */
    public static function url(?string $path): string
    {
        if (blank($path)) {
            return '';
        }

        return config('filesystems.disks.public.driver') === 'local'
            ? asset('storage/'.$path)
            : Storage::disk('public')->url($path);
    }
}
