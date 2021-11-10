<?php

namespace NewProject\App\Rules;

class UserRule
{
    public static $rules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
    ];


    public static $messages = [
        'first_name.required' => 'First name is required',
        'last_name.required' => 'Last name is required',
        'email.required' => 'Email is required',
        'email.email' => 'Invaid email',
        'phone.required' => 'Contact no. is required',
    ];
}
