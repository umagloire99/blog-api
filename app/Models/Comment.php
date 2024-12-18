<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Traits\HasComments;
use App\Contracts\Commentable;

class Comment extends Model implements Commentable
{
    use HasComments;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'commentable_id',
        'commentable_type',
        'content'
    ];

    /**
     *
     * @var array
     */
    protected $with = [
        'comments',
        'user'
    ];

    /**
     * user
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * commentable
     *
     * @return MorphTo
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}
