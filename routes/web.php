<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/profile/{id}', 'UserController@show')->name('profile');
Route::post('/profile/{id}/addRole', 'UserController@addRole')->name('add-role')->middleware('auth', '2fa');
Route::get('/profile/{id}/delRole/{role}', 'UserController@delRole')->name('remove-role')->middleware('auth', '2fa');
Route::post('/profile/{id}/delRole/{role}', 'UserController@delRoleConfirm')->name('remove-role-confirm')
    ->middleware('auth', '2fa');

Route::get('/acp', 'HomeController@acp')->name('acp')->middleware('auth', '2fa');
Route::get('/acp/users', 'UserController@index')->name('user-list')->middleware('auth', '2fa');
Route::get('/acp/roles', 'RoleController@index')->name('role-list')->middleware('auth', '2fa');


// Stuff for the 2FA plugin
Route::group(['prefix' => '2fa'], function () {
    Route::get('/', 'LoginSecurityController@show2faForm')->name('2fa-settings');
    Route::post('/generateSecret', 'LoginSecurityController@generate2faSecret')->name('generate2faSecret');
    Route::post('/enable2fa', 'LoginSecurityController@enable2fa')->name('enable2fa');
    Route::post('/disable2fa', 'LoginSecurityController@disable2fa')->name('disable2fa');

    // 2fa middleware
    Route::post('/2faVerify', function () {
        return redirect(URL()->previous());
    })->name('2faVerify')->middleware('2fa');
});

// test middleware
Route::get('/test_middleware', function () {
    return "2FA middleware work!";
})->middleware(['auth', '2fa']);
