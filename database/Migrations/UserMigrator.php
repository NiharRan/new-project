<?php

namespace NewProject\Database\Migrations;

class UserMigrator
{
    static $tableName = 'nihar_users';

    public static function migrate()
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();

        $table = $wpdb->prefix . static::$tableName;

        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            $sql = "CREATE TABLE $table (
                `id` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `first_name` VARCHAR(100) NOT NULL,
                `last_name` VARCHAR(100) NOT NULL,
                `slug` VARCHAR(100) NULL,
                `avatar` TEXT NULL,
                `email` VARCHAR(200) NULL,
                `phone` VARCHAR(100) NULL,
                `status` TINYINT NULL,
                `created_at` TIMESTAMP NULL,
                `updated_at` TIMESTAMP NULL
            ) $charsetCollate;";
            dbDelta($sql);
        }
    }
}
