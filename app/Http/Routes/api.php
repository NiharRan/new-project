<?php


$router->prefix('projects')->group(function ($router) {
    $router->get('/', 'ProjectController@index'); 
    $router->post('/', 'ProjectController@save');
    $router->get('/{slug}', 'ProjectController@get');
    $router->put('/{id}', 'ProjectController@update')->int('id');
    $router->delete('/{id}', 'ProjectController@destroy')->int('id');

    $router->get('/users/{id}', 'ProjectController@users');
});

$router->prefix('tasks')->group(function ($router) {
    $router->get('/', 'TaskController@index');
    $router->post('/', 'TaskController@save');
    $router->get('/{slug}', 'TaskController@get');
    $router->put('/{id}', 'TaskController@update')->int('id');
    $router->delete('/{id}', 'TaskController@destroy')->int('id');
});

$router->prefix('comments')->group(function ($router) {
    $router->get('/', 'CommentController@index');
    $router->post('/', 'CommentController@save');
    $router->put('/{id}', 'CommentController@update')->int('id');
    $router->delete('/{id}', 'CommentController@destroy')->int('id');
});

$router->prefix('users')->group(function ($router) {
    $router->get('/', 'UserController@index');
    $router->post('/', 'UserController@save');
    $router->get('/{slug}', 'UserController@get');
    $router->put('/{id}', 'UserController@update')->int('id');
    $router->delete('/{id}', 'UserController@destroy')->int('id');
});

/**
 * @var $router NewProject\App\Http\Router
 */

$router->get('/welcome', 'WelcomeController@index');
