<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group([
    // 'middleware' => 'api',
    'prefix' => 'auth',
    'namespace' => 'Auth'
], function () use ($router) {
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');
    $router->post('me', 'AuthController@me');
});

$router->group([
    'middleware' => 'auth:api',
    'prefix' => 'api',
], function () use ($router) {
    $router->group([
        'prefix' => 'mahasiswa',
        'namespace' => 'Api',
        'middleware' => 'auth:api'
    ], function () use ($router) {
        $router->get('/', 'MahasiswaController@index');
        $router->get('/all', 'MahasiswaController@all');
        $router->post('/insert', 'MahasiswaController@insert');
        $router->put('/update', 'MahasiswaController@update');
        $router->delete('/delete', 'MahasiswaController@delete');
    });
});
