<?php

namespace NewProject\App\Rules;

class TaskRule
{
    public static $rules = [
        'name' => 'required',
        'project' => 'required',
        'user' => 'required',
    ];


    public static $messages = [
        'name.required' => 'Task name is required',
        'project.required' => 'Project is required',
        'user.required' => 'User is required',
    ];
}
