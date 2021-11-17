<?php

namespace NewProject\App\Modules;

class PermissionManager
{
    public static function permissions()
    {
        return [
            'new_pro_view_dashboard',
            'new_pro_view_users',
            'new_pro_create_user',
            'new_pro_edit_user',
            'new_pro_destroy_user',
            'new_pro_view_projects',
            'new_pro_create_project',
            'new_pro_edit_project',
            'new_pro_destroy_project',
            'new_pro_view_tasks',
            'new_pro_create_task',
            'new_pro_edit_task',
            'new_pro_destroy_task',
        ];
    }
}
