<?php

namespace NewProject\App\Hooks\Handlers;

class UserHandler
{
    public static function filterUsers($users)
    {
        foreach ($users as $key => $user) {
            $users[$key]->total_projects = $user->total_projects > 0
                ? $user->total_projects
                : 'No Project Assigned';
        }

        return $users;
    }
}
