<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth')->group(function () {
    // Users
    Route::view('/users/account', 'users.account')->name('users.account');
    Route::put('/users/{user}/profile-update', 'Users\UpdateProfileController')->name('users.profile-update');
    Route::put('/users/{user}/password-update', 'Users\UpdatePasswordController')->name('users.password-update');

    // Orders
    Route::get('menus/{menu}/orders/index', 'OrderController@list')->name('orders');
    Route::post('menus/{menu}/orders/bulk-destroy', 'OrderController@bulkDestroy')->name('orders.bulk-destroy');
    Route::put('menus/{menu}/orders/{order}/complete', 'Orders\CompleteOrderController')->name('orders.complete');
    Route::resource('menus/{menu}/orders', 'OrderController');

    // Menus
    Route::post('menus/get-reports', 'Menus\ReportController')->name('menus.get-reports');
    Route::view('menus/report', 'menus.report')->name('menus.report');
    Route::get('menus/list', 'MenuController@list')->name('menus');
    Route::post('menus/bulk-destroy', 'MenuController@bulkDestroy')->name('menus.bulk-destroy');
    Route::resource('menus', 'MenuController');
});
