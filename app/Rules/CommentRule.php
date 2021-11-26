<?php

namespace NewProject\App\Rules;

class CommentRule
{
    public static $rules = [
        'message' => 'required',
        'task' => 'required',
        'user' => 'required',
    ];


    public static $messages = [
        'message.required' => 'Comment is required',
        'task.required' => 'Task is required',
        'user.required' => 'User is required',
    ];
}
