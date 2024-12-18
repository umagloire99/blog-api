<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Sluggable
{
    /**
     * Boot the sluggable trait for the model.
     *
     * @return void
     */
    public static function bootSluggable()
    {
        static::saving(function ($model) {
            if ($model->isDirty($model->slugSouceField())) {
                $model->slug = $model->generateSlug();
            }
        });
    }

    /**
     * Generate a slug based on slug source field.
     *
     * @return string
     */
    public function generateSlug()
    {
        $sourceFieldValue = $this->{$this->slugSouceField()};

        // Create the slug
        $slug = Str::slug($sourceFieldValue);

        // Ensure the slug is unique
        while (static::where('slug', $slug)->exists()) {
            $slug = "$slug-";
        }

        return $slug;
    }

    /**
     * slugSouceField
     *
     * @return string
     */
    abstract public function slugSouceField(): string;
}
