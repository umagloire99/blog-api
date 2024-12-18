<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Comment;
use App\Contracts\Commentable;

trait CanComment
{
    /**
     * comment
     *
     * @param  Commentable $commentable
     * @param  string $content
     */
    public function comment(Commentable $commentable, string $content)
    {
        $comment = $commentable->comments()->create([
            'content' => $content,
            'user_id' => $this->id
        ]);

        return $comment;
    }

    /**
     * comments
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
