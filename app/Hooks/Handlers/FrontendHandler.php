<?php

namespace NewProject\App\Hooks\Handlers;

use NewProject\App\App;

class FrontendHandler
{
    public function render()
    {
        $this->enqueueAssets();
    }

    public function enqueueAssets()
    {
        $assets = PLUGIN_DIR . 'assets/';
        $slug = random_bytes(1);

        wp_enqueue_style(
            $slug . '_frontend_app',
            $assets . 'css/custom.css'
        );

        do_action($slug . '_loading_app');

        wp_enqueue_script(
            $slug . '_forntend',
            $assets . 'js/custom.js',
            array('jquery'),
            '1.0',
            true
        );
    }
}
