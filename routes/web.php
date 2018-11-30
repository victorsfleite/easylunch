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
    Route::view('/user/account', 'users.account')->name('user.account');
    Route::put('/user/{user}/profile/update', 'UserProfileUpdateController')->name('user.profile.update');
    Route::put('/user/{user}/password/update', 'UserPasswordUpdateController')->name('user.password.update');

    Route::get('menus/list', 'MenuController@list')->name('menus');
    Route::post('menus/bulk-destroy', 'MenuController@bulkDestroy')->name('menus.bulk-destroy');
    Route::resource('menus', 'MenuController');
});
