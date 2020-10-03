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
Route::post('/profile/{id}/delRole/{role}', 'UserController@delRoleConfirm')->name('remove-role-confirm')->middleware('auth', '2fa');

Route::get('/search', 'HomeController@search')->name('search');
Route::post('/search', 'HomeController@search')->name('search');

Route::get('/game/{id}', 'GameController@show')->name('game');
Route::get('/game/{id}/edit', 'GameController@edit')->name('game-edit')->middleware('auth', '2fa');
Route::get('/game/{id}/edit/servers', 'ServerController@edit')->name('game-manage-servers')->middleware('auth', '2fa');
Route::post('/game/{id}/edit/realm/add', 'ServerController@storeRealm')->name('game-realm-add')->middleware('auth', '2fa');
Route::post('/game/{id}/edit/server/add', 'ServerController@storeServer')->name('game-server-add')->middleware('auth', '2fa');
Route::get('/realm/{id}/edit/', 'ServerController@editRealm')->name('game-realm-edit')->middleware('auth', '2fa');
Route::post('/realm/{id}/update/', 'ServerController@updateRealm')->name('game-realm-update')->middleware('auth', '2fa');
Route::get('/server/{id}/edit/', 'ServerController@editServer')->name('game-server-edit')->middleware('auth', '2fa');
Route::post('/server/{id}/update/', 'ServerController@updateServer')->name('game-server-update')->middleware('auth', '2fa');

Route::get('/community/create', 'CommunityController@create')->name('community-create')->middleware('auth', '2fa');
Route::post('/community/create', 'CommunityController@store')->name('community-create')->middleware('auth', '2fa');
Route::get('/community/{id}/edit', 'CommunityController@edit')->name('community-edit')->middleware('auth', '2fa');
Route::post('/community/{id}/edit', 'CommunityController@update')->name('community-update')->middleware('auth', '2fa');

Route::get('/community/{id}', 'CommunityController@show')->name('community');

Route::get('/forgePowerUp', 'HomeController@forge')->name('forge-power-up')->middleware('auth', '2fa');
Route::get('/forgePowerUp/{id}', 'HomeController@forgePowerUp')->name('forge-grant')->middleware('auth', '2fa');

Route::group(['prefix' => 'guild', 'as' => 'guild-',
    'middleware' => [
        'auth',
        '2fa',
    ],
], function () {
    Route::get('/create', 'GuildController@create')->name('create');
    Route::post('/create', 'GuildController@create')->name('create');
    Route::get('/{id}/edit', 'GuildController@edit')->name('edit');
    Route::post('/{id}/edit', 'GuildController@update')->name('update');
    Route::get('/{id}/apps', 'AppController@guildIndex')->name('apps');
    Route::get('/{id}/app/create', 'AppController@create')->name('app-create');
    Route::post('/{id}/app/create', 'AppController@store')->name('app-create');
});

Route::get('/guild/{id}', 'GuildController@show')->name('guild');

Route::post('/guild/{id}/joinCommunity', 'GuildController@joinCommunity')->name('community-join')->middleware('auth', '2fa');
Route::post('/guild/{id}/joinCommunityConfirmed', 'GuildController@joinCommunityConfirm')->name('community-join-confirm')->middleware('auth', '2fa');
Route::get('/guild/{id}/leaveCommunity', 'GuildController@leaveCommunity')->name('community-leave')->middleware('auth', '2fa');
Route::post('/guild/{id}/leaveCommunityConfirmed', 'GuildController@leaveCommunityConfirm')->name('community-leave-confirm')->middleware('auth', '2fa');

Route::group(['prefix' => 'application', 'as' => 'app-',
    'middleware' => [
        'auth',
        '2fa',
    ],
], function () {
    Route::get('/{id}', 'AppController@submit')->name('submit');
    Route::get('/submission/{id}', 'AppController@submission')->name('submission');
    Route::post('/{id}/submit', 'AppController@submitAnswers')->name('submit-answers');
    Route::get('/{id}/edit', 'AppController@edit')->name('edit');
    Route::get('/{id}/manage', 'AppController@manage')->name('manage');
    Route::post('/{id}/update', 'AppController@update')->name('update');

    Route::post('/{id}/question/add', 'AppController@addQuestion')->name('question-add');
});

Route::group([
    'prefix'    => 'acp',
    'middleware' => [
        'auth',
        '2fa',
    ],
], function () {
    
    Route::get('/', 'HomeController@acp')->name('acp');
    Route::get('/users', 'UserController@index')->name('user-list');
    Route::get('/roles', 'RoleController@index')->name('role-list');
    Route::get('/guilds', 'GuildController@index')->name('guild-list');
    Route::get('/communities', 'CommunityController@index')->name('community-list');

    Route::get('/games', 'GameController@index')->name('game-list');
    Route::get('/games/pending', 'GameController@pending')->name('game-list-pending');
    Route::post('/games/pending/{id}/approve', 'GameController@approvePending')->name('game-approve-pending');
    Route::post('/games/pending/{id}/genre', 'GameController@setPendingGenre')->name('game-set-pending-genre');
    Route::post('/games/pending/{id}/reject', 'GameController@rejectPending')->name('game-reject-pending');
    Route::get('/games/add', 'GameController@create')->name('game-add');
    Route::post('/games/add', 'GameController@store')->name('game-create');
    Route::post('/game/{id}/edit', 'GameController@update')->name('game-update');

    Route::get('/genres', 'GenreController@index')->name('genre-list');
    Route::get('/genre/{id}/edit', 'GenreController@edit')->name('genre-edit');
    Route::post('/genre/{id}/edit', 'GenreController@update')->name('genre-update');
    Route::get('/genre/add', 'GenreController@create')->name('genre-add');
    Route::post('/genre/add', 'GenreController@store')->name('genre-create');
});

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

Route::get('/testInviteGeneration', 'HomeController@test')->name('test');
