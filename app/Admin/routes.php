<?php

use Illuminate\Routing\Router;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\Route;
use App\Admin\Controllers\PlacesController;
use App\Admin\Controllers\RegionsController;
use App\Admin\Controllers\CompaniesController;
use App\Admin\Controllers\CountriesController;
use App\Admin\Controllers\RentsController;
use App\Admin\Controllers\SchedulesController;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {
    $router->resource('auth/users', AdminUserController::class);
    $router->get('/', 'HomeController@index')->name('home');

    $router->resource('/companies', CompaniesController::class);
    $router->resource('/places', PlacesController::class);
    $router->resource('/schedules', SchedulesController::class);
    $router->resource('/rents', RentsController::class);


});
