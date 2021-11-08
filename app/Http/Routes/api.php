<?php


$router->prefix('projects')->group(function ($router) {
    $router->get('/', 'ProjectController@index');
    $router->post('/', 'ProjectController@save');
    $router->get('/{projectId}', 'ProjectController@get')->int('id');
    $router->put('/{projectId}', 'ProjectController@update')->int('id');
    $router->delete('/{projectId}', 'ProjectController@destroy')->int('id');
});

$router->prefix('tasks')->group(function ($router) {
    $router->get('/', 'TaskController@index');
    $router->post('/', 'TaskController@save');
    $router->get('/{taskId}', 'TaskController@get')->int('id');
    $router->put('/{taskId}', 'TaskController@update')->int('id');
    $router->delete('/{taskId}', 'TaskController@destroy')->int('id');
});

$router->prefix('users')->group(function ($router) {
    $router->get('/', 'UserController@index');
    $router->post('/', 'UserController@save');
    $router->get('/{userId}', 'UserController@get')->int('id');
    $router->put('/{userId}', 'UserController@update')->int('id');
    $router->delete('/{userId}', 'UserController@destroy')->int('id');
});

/**
 * @var $router NewProject\App\Http\Router
 */

$router->get('/welcome', 'WelcomeController@index');
