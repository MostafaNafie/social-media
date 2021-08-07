<?php

namespace App\Observers;

use App\Models\Comment;
use Illuminate\Support\Facades\Redis;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        $this->postCommentsCount($comment);
    }

    /**
     * Handle the Comment "updated" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function updated(Comment $comment)
    {
        //
    }

    /**
     * Handle the Comment "deleted" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function deleted(Comment $comment)
    {
        $this->postCommentsCount($comment, 'decr');
    }

    /**
     * Handle the Comment "restored" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function restored(Comment $comment)
    {
        $this->postCommentsCount($comment);
    }

    /**
     * Handle the Comment "force deleted" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function forceDeleted(Comment $comment)
    {
        if ($comment->deleted_at == null) {
            $this->postCommentsCount($comment, 'decr');
        }

    }


    private function postCommentsCount(Comment $comment, string $fun = 'incr')
    {
        $postId = $comment->post_id;
        $key = "post:{$postId}:comments:count";

        if ($count = Redis::get($key)) {
            Redis::$fun($key);
            return;
        }

        Redis::set($key, $comment->post->comments()->count());
    }
}
