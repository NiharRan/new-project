<?php

namespace NewProject\App\Rules;

class ProjectRule
{
    public static $rules = [
        'name' => 'required',
        'users' => 'required|array'
    ];


    public static $messages = [
        'name.required' => 'Project name is required',
        'users.required' => 'Users is required',
        'users.array' => 'Invalid data',
    ];
}
