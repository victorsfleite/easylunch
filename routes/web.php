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

Route::redirect('/', '/home');

Auth::routes();

Route::get('home', 'HomeController@index')->name('home');

Route::middleware('auth')->group(function () {
    Route::get('today', 'Menus\MenuOfTheDayController')->name('menus.today');

    // Users
    Route::view('/users/account', 'users.account')->name('users.account');
    Route::put('/users/{user}/profile-update', 'Users\UpdateProfileController')->name('users.profile-update');
    Route::put('/users/{user}/password-update', 'Users\UpdatePasswordController')->name('users.password-update');
    Route::get('users/stop-impersonating', 'Users\ImpersonationController@stopImpersonating')
        ->name('users.stop-impersonating');

    // Orders
    Route::get('menus/{menu}/orders/index', 'OrderController@list')->name('orders');
    Route::post('menus/{menu}/orders/bulk-destroy', 'OrderController@bulkDestroy')->name('orders.bulk-destroy');
    Route::put('menus/{menu}/orders/{order}/complete', 'Orders\CompleteOrderController')->name('orders.complete');
    Route::resource('menus/{menu}/orders', 'OrderController');

    // Reports
    Route::post('reports/orders', 'Reports\ReportController')->name('reports.orders');
    Route::post('reports/users', 'Reports\UserReportsController')->name('reports.users');
    // Menus
    Route::view('menus/report', 'menus.report')->name('menus.report');
    Route::get('menus/list', 'MenuController@list')->name('menus');
    Route::post('menus/bulk-destroy', 'MenuController@bulkDestroy')->name('menus.bulk-destroy');
    Route::resource('menus', 'MenuController');

    // Options
    Route::apiResource('options', 'OptionController', ['except' => ['show']]);

    Route::middleware('admin')->group(function () {
        // Users
        Route::get('/users/roles', 'Users\GetRolesController')->name('users.roles');
        Route::get('users/index', 'UserController@list')->name('users');
        Route::post('users/bulk-destroy', 'UserController@bulkDestroy')->name('users.bulk-destroy');
        Route::get('users/{user}/impersonate', 'Users\ImpersonationController@impersonate')->name('users.impersonate');
        Route::get('users/all', 'Users\GetAllUsersController')->name('users.all');
        Route::resource('users', 'UserController');

        // Orders Operations
        Route::put('orders/mark-paid-many', 'Orders\MarkPaidManyController')->name('orders.mark-paid-many') ;
        Route::put('orders/{order}/mark-paid', 'Orders\MarkPaidController')->name('orders.mark-paid');
        Route::put('orders/{order}/update-owner', 'Orders\UpdateOwnerController')->name('orders.update-owner');
        Route::post('orders/send-invoice', 'Orders\SendInvoicesController@sendAll')->name('orders.send-invoices');
        Route::post('orders/send-invoice/user/{user}', 'Orders\SendInvoicesController@sendToUser')->name('orders.send-invoice-to-user');
    });
});
