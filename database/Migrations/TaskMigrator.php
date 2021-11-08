<?php

namespace NewProject\Database\Migrations;

class TaskMigrator
{
    static $tableName = 'nihar_tasks';

    public static function migrate()
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();

        $table = $wpdb->prefix . static::$tableName;

        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            $sql = "CREATE TABLE $table (
                `id` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `project_id` BIGINT(20) UNSIGNED NOT NULL,
                `user_id` BIGINT(20) UNSIGNED NOT NULL,
                `name` VARCHAR(100) NOT NULL,
                `slug` TEXT NULL,
                `status` TINYINT NULL,
                `created_at` TIMESTAMP NULL,
                `updated_at` TIMESTAMP NULL
            ) $charsetCollate;";
            dbDelta($sql);
        }
    }
}
