<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

/**
 * @group Comments
 * @authenticated
 *
 * APIs for comments.
 */
class CommentController extends Controller
{
    use ApiResponse;

    /**
     * Leave a reply to a comment
     *
     * @param  CommentRequest $request
     * @param  Comment $comment
     */
    public function addComment(CommentRequest $request, Comment $comment)
    {
        $comment = $request->user()->comment($comment, $request->content);

        return $this->success(
            'Comment added successfully.',
            $comment
        );
    }


    /**
     * Update the specified comment.
     *
     */
    public function update(Request $request, Comment $comment)
    {
        $comment->update(['content' => $request->content]);

        return $this->success(
            'Comment updated successfully.',
            $comment
        );
    }

    /**
     * Delete the specified comment.
     *
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return $this->success(
            'Comment has been successfully deleted.'
        );
    }
}
