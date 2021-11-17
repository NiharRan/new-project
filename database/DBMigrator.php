<?php

namespace NewProject\Database;

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

use NewProject\Database\Migrations\ProjectMigrator;
use NewProject\Database\Migrations\TaskMigrator;
use NewProject\Database\Migrations\UserMigrator;
use NewProject\Database\Migrations\UserProjectMigrator;
use NewProject\Database\Migrations\TaskActivityMigrator;

class DBMigrator
{
    protected static $models = [
        UserMigrator::class,
        ProjectMigrator::class,
        UserProjectMigrator::class,
        TaskMigrator::class,
        TaskActivityMigrator::class
    ];
    public static function run($network_wide = false)
    {
        self::migrate();
    }

    public static function migrate()
    {
        foreach (static::$models as $model) {
            $model::migrate();
        }
    }
}
