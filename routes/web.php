<?php

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
    // 6KgkzsH%uRwb!BAzow(c
});

$router->group(['prefix' => 'user/'], function ($router) {
    $router->post('/{cmd}/', 'UserController@postCommands');
    $router->post('/{cmd}/{param}/', 'UserController@postCmdWithParam1');
});
$router->group(['prefix' => 'license/'], function ($router) {
    $router->post('/{cmd}/', 'LicenseController@postCommands');
    $router->post('/{cmd}/{param}/', 'LicenseController@postCmdWithParam1');
});
$router->group(['prefix' => 'price/'], function ($router) {
    $router->post('/{cmd}/', 'PriceController@postCommands');
    $router->post('/{cmd}/{param}/', 'PriceController@postCmdWithParam1');
});
$router->group(['prefix' => 'contact/'], function ($router) {
    $router->post('/{cmd}/', 'ContactController@postCommands');
    $router->post('/{cmd}/{param}/', 'ContactController@postCmdWithParam1');
});