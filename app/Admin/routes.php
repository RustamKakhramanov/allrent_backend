<?php

use Illuminate\Routing\Router;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\Route;
use App\Admin\Controllers\RegionsController;
use App\Admin\Controllers\CountriesController;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {
    $router->resource('auth/users', AdminUserController::class);

});
