<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Upload extends Model
{
    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'ref',
        'name',
        'path',
        'type',
        'use_as',
        'uploadable_id',
        'uploadable_type'
    ];

    /**
     * appends
     *
     * @var array
     */
    protected $appends = [
        'storage_url'
    ];

    /**
     * get Storage url
     *
     * @return string
     */
    public function getStorageUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    /**
     * uploadable
     *
     * @return MorphTo
     */
    public function uploadable(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function booted()
    {
        static::deleting(function ($model) {
            Storage::disk('public')->delete($model->path);
        });
    }
}
