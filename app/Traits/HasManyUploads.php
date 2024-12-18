<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Upload;
use App\Enums\UploadUseAsEnum;

trait HasManyUploads
{
    /**
     * uploads
     *
     * @return MorphMany
     */
    public function uploads(): MorphMany
    {
        return $this->morphMany(Upload::class, 'uploadable');
    }

    /**
     * gallery
     *
     * @return MorphMany
     */
    public function gallery(): MorphMany
    {
        return $this->uploads()->where('use_as', UploadUseAsEnum::GALLERY->value);
    }

    /**
     * image
     *
     * @return MorphOne
     */
    public function image(): MorphOne
    {
        return $this->morphOne(Upload::class, 'uploadable')->where('use_as', UploadUseAsEnum::IMAGE->value);
    }

    /**
     * Handle file uploads dynamically.
     *
     * @param mixed $files
     * @param string $useAs
     */
    public function handleUploads($files, UploadUseAsEnum $useAs)
    {
        $files = is_array($files) ? $files : [$files];

        foreach ($files as $file) {
            $path = $file->storePublicly('uploads', ['disk' => 'public']);

            $this->uploads()->create([
                'ref' => Str::uuid(),
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'type' => $this->getFileType($file),
                'use_as' => $useAs,
            ]);
        }
    }

    /**
     * Determine the file type based on its MIME type.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    private function getFileType(UploadedFile $file)
    {
        $mimeType = $file->getMimeType();

        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        }

        return 'unknown';
    }
}
