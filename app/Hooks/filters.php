<?php

/**
 * All registered filter's handlers should be in app\Hooks\Handlers,
 * addFilter is similar to add_filter and addCustomFlter is just a
 * wrapper over add_filter which will add a prefix to the hook name
 * using the plugin slug to make it unique in all wordpress plugins,
 * ex: $app->addCustomFilter('foo', ['FooHandler', 'handleFoo']) is
 * equivalent to add_filter('slug-foo', ['FooHandler', 'handleFoo']).
 */

use NewProject\App\Hooks\Handlers\CommentHandler;
use NewProject\App\Hooks\Handlers\UserHandler;

/**
 * $app
 * @var WPFluent\Foundation\Application
 */

add_filter('new-project/filter_user_data', function ($users) {
    return UserHandler::filterUsers($users);
});
add_filter('new-project/filter_comments', function ($comments) {
    return CommentHandler::filterComments($comments);
});
