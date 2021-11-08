<?php

namespace NewProject\Database\Migrations;

class UserProjectMigrator
{
    static $tableName = 'nihar_user_project';

    public static function migrate()
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();

        $table = $wpdb->prefix . static::$tableName;

        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            $sql = "CREATE TABLE $table (
                `user_id` BIGINT(20) UNSIGNED NOT NULL,
                `project_id` BIGINT(20) UNSIGNED NOT NULL,
                `created_at` TIMESTAMP NULL,
                `updated_at` TIMESTAMP NULL
            ) $charsetCollate;";
            dbDelta($sql);
        }
    }
}
