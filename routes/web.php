<?php

use App\Http\Controllers\GuildController;
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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('/profile/{id}', 'UserController@show')->name('profile');

Route::get('/profile/{id}/edit', 'UserController@edit')->name('profile-edit')->middleware('auth', '2fa');
Route::post('/profile/{id}/edit', 'UserController@update')->name('profile-update')->middleware('auth', '2fa');

Route::post('/profile/{id}/addRole', 'UserController@addRole')->name('add-role')->middleware('auth', '2fa');
Route::get('/profile/{id}/delRole/{role}', 'UserController@delRole')->name('remove-role')->middleware('auth', '2fa');
Route::post('/profile/{id}/delRole/{role}', 'UserController@delRoleConfirm')->name('remove-role-confirm')
    ->middleware('auth', '2fa');

Route::post('/search', 'HomeController@search')->name('search');
Route::get('/search', 'HomeController@search')->name('search');

Route::get('/acp', 'HomeController@acp')->name('acp')->middleware('auth', '2fa');
Route::get('/acp/users', 'UserController@index')->name('user-list')->middleware('auth', '2fa');
Route::get('/acp/roles', 'RoleController@index')->name('role-list')->middleware('auth', '2fa');

Route::get('/game/{id}', 'GameController@show')->name('game');
Route::get('/game/{id}/edit', 'GameController@edit')->name('game-edit')->middleware('auth', '2fa');
Route::get('/game/{id}/edit/servers', 'ServerController@edit')->name('game-manage-servers')->middleware('auth', '2fa');
Route::post('/game/{id}/edit/realm/add', 'ServerController@storeRealm')->name('game-realm-add')->middleware('auth', '2fa');
Route::post('/game/{id}/edit/server/add', 'ServerController@storeServer')->name('game-server-add')->middleware('auth', '2fa');
Route::get('/realm/{id}/edit/', 'ServerController@editRealm')->name('game-realm-edit')->middleware('auth', '2fa');
Route::post('/realm/{id}/update/', 'ServerController@updateRealm')->name('game-realm-update')->middleware('auth', '2fa');
Route::get('/server/{id}/edit/', 'ServerController@editServer')->name('game-server-edit')->middleware('auth', '2fa');
Route::post('/server/{id}/update/', 'ServerController@updateServer')->name('game-server-update')->middleware('auth', '2fa');

Route::get('/acp/games', 'GameController@index')->name('game-list')->middleware('auth', '2fa');
Route::get('/acp/games/pending', 'GameController@pending')->name('game-list-pending')->middleware('auth', '2fa');
Route::post('/acp/games/pending/{id}/approve', 'GameController@approvePending')->name('game-approve-pending')->middleware('auth', '2fa');
Route::post('/acp/games/pending/{id}/genre', 'GameController@setPendingGenre')->name('game-set-pending-genre')->middleware('auth', '2fa');
Route::post('/acp/games/pending/{id}/reject', 'GameController@rejectPending')->name('game-reject-pending')->middleware('auth', '2fa');
Route::get('/acp/games/add', 'GameController@create')->name('game-add')->middleware('auth', '2fa');
Route::post('/acp/games/add', 'GameController@store')->name('game-create')->middleware('auth', '2fa');
Route::post('/acp/game/edit/{id}', 'GameController@update')->name('game-update')->middleware('auth', '2fa');
Route::get('/acp/genres', 'GenreController@index')->name('genre-list')->middleware('auth', '2fa');
Route::get('/acp/genre/edit/{id}', 'GenreController@edit')->name('genre-edit')->middleware('auth', '2fa');
Route::post('/acp/genre/edit/{id}', 'GenreController@update')->name('genre-update')->middleware('auth', '2fa');
Route::get('/acp/genre/add', 'GenreController@create')->name('genre-add')->middleware('auth', '2fa');
Route::post('/acp/genre/add', 'GenreController@store')->name('genre-create')->middleware('auth', '2fa');

Route::get('/guilds', 'GuildController@index')->name('guild-list')->middleware('auth', '2fa');
Route::get('/guild/create', 'GuildController@create')->name('guild-create')->middleware('auth', '2fa');
Route::post('/guild/create', 'GuildController@create')->name('guild-create')->middleware('auth', '2fa');
Route::get('/guild/{id}', 'GuildController@show')->name('guild');
Route::get('/guild/{id}/edit', 'GuildController@edit')->name('guild-edit')->middleware('auth', '2fa');
Route::post('/guild/{id}/edit', 'GuildController@update')->name('guild-update')->middleware('auth', '2fa');

Route::post('/guild/{id}/joinCommunity', 'GuildController@joinCommunity')->name('community-join')->middleware('auth', '2fa');
Route::post('/guild/{id}/joinCommunityConfirmed', 'GuildController@joinCommunityConfirm')->name('community-join-confirm')->middleware('auth', '2fa');
Route::get('/guild/{id}/leaveCommunity', 'GuildController@leaveCommunity')->name('community-leave')->middleware('auth', '2fa');
Route::post('/guild/{id}/leaveCommunityConfirmed', 'GuildController@leaveCommunityConfirm')->name('community-leave-confirm')->middleware('auth', '2fa');

Route::get('acp/communities', 'CommunityController@index')->name('community-list')->middleware('auth', '2fa');
Route::get('/community/create', 'CommunityController@create')->name('community-create')->middleware('auth', '2fa');
Route::post('/community/create', 'CommunityController@store')->name('community-create')->middleware('auth', '2fa');
Route::get('/community/{id}/edit', 'CommunityController@edit')->name('community-edit')->middleware('auth', '2fa');
Route::post('/community/{id}/edit', 'CommunityController@update')->name('community-update')->middleware('auth', '2fa');

Route::get('/community/{id}', 'CommunityController@show')->name('community');

Route::get('/forgePowerUp', 'HomeController@forge')->name('forge-power-up')->middleware('auth', '2fa');
Route::get('/forgePowerUp/{id}', 'HomeController@forgePowerUp')->name('forge-grant')->middleware('auth', '2fa');

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

Route::get('/ourMission', 'HomeController@mission')->name('our-mission');
