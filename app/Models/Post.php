<?php

namespace App\Models;

use App\Contracts\Commentable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Sluggable;
use App\Traits\HasComments;
use Laravel\Scout\Searchable;
use App\Traits\HasManyUploads;

class Post extends Model implements Commentable
{
    use HasFactory, Searchable, HasComments, HasManyUploads, Sluggable;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = ['title', 'slug', 'content'];

    /**
     * slugSouceField
     *
     * @return string
     */
    public function slugSouceField(): string
    {
        return 'title';
    }

    /**
     * user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * tags
     *
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * toSearchableArray
     *
     */
    public function toSearchableArray()
    {
        return [
            'title' => ''
        ];
    }

    /**
     * booted
     *
     */
    protected static function booted()
    {
        static::deleting(function ($model) {
            foreach ($model->uploads as $upload) {
                $upload->delete();
            }
        });
    }
}
