<?php

namespace NewProject\App\Hooks\Handlers;

use NewProject\Database\DBMigrator;
use NewProject\Database\DBSeeder;

class ActivationHandler
{
    public function handle($network_wide = false)
    {
        DBMigrator::run($network_wide);
        DBSeeder::run();
    }
}
