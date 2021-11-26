<?php

namespace NewProject\App\Hooks\Handlers;

class CommentHandler
{
    public static function filterComments($comments)
    {
        foreach ($comments as $key => $user) {
            $comments[$key]->total_replies = $user->total_replies > 0
                ? $user->total_replies
                : 'No Activity';
        }

        return $comments;
    }
}
 