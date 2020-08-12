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
Route::post('/profile/{id}/addRole', 'UserController@addRole')->name('add-role');
Route::get('/profile/{id}/delRole/{role}', 'UserController@delRole')->name('remove-role');
Route::post('/profile/{id}/delRole/{role}', 'UserController@delRoleConfirm')->name('remove-role-confirm');

Route::get('/acp', 'HomeController@acp')->name('acp');