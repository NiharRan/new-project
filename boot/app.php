<?php

use NewProject\Framework\Foundation\Application;
use NewProject\App\Hooks\Handlers\ActivationHandler;
use NewProject\App\Hooks\Handlers\DeactivationHandler;

return function($file) {

    register_activation_hook($file, function() {
        (new ActivationHandler)->handle();
    });

    register_deactivation_hook($file, function() {
        (new DeactivationHandler)->handle();
    });

    add_action('plugins_loaded', function() use ($file) {
        do_action('newproject_loaded', new Application($file));
    });
};
