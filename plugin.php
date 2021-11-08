<?php defined('ABSPATH') or die;

/*
Plugin Name: New Project
Description: New Project WordPress Plugin
Version: 1.0.0
Author: 
Author URI: 
Plugin URI: 
License: GPLv2 or later
Text Domain: new-project
Domain Path: /language
*/

require __DIR__.'/vendor/autoload.php';

call_user_func(function($bootstrap) {
    $bootstrap(__FILE__);
}, require(__DIR__.'/boot/app.php'));
