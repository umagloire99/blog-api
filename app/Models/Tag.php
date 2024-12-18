<?php

namespace App\Models;

use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;

class Tag extends Model
{
    use HasFactory, Searchable, Sluggable;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = ['name', 'slug'];

    /**
     * slugSouceField
     *
     * @return string
     */
    public function slugSouceField(): string
    {
        return 'name';
    }

    /**
     * posts
     *
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    /**
     * toSearchableArray
     *
     */
    public function toSearchableArray()
    {
        return [
            'name' => ''
        ];
    }
}
