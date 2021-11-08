<?php

/**
 * Enable Query Log
 */
if (!function_exists('newproject_eql')) {
    function wpfluent_eql()
    {
        defined('SAVEQUERIES') || define('SAVEQUERIES', true);
    }
}

/**
 * Get Query Log
 */
if (!function_exists('newproject_gql')) {
    function newproject_gql()
    {
        $result = [];
        foreach ((array)$GLOBALS['wpdb']->queries as $key => $query) {
            $result[++$key] = array_combine([
                'query', 'execution_time'
            ], array_slice($query, 0, 2));
        }
        return $result;
    }
}

