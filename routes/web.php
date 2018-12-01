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
    Route::view('/users/account', 'users.account')->name('users.account');
    Route::put('/users/{user}/profile-update', 'Users\UserProfileUpdateController')->name('users.profile-update');
    Route::put('/users/{user}/password-update', 'Users\UserPasswordUpdateController')->name('users.password-update');

    Route::get('menus/list', 'MenuController@list')->name('menus');
    Route::post('menus/bulk-destroy', 'MenuController@bulkDestroy')->name('menus.bulk-destroy');
    Route::resource('menus', 'MenuController');
});
