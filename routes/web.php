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

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;

use App\User;

use Illuminate\Support\Facades\Route;


// Page's routes

Route::get('registration', 'PagesController@registration');

Route::get('/', 'PagesController@login');

Route::get('/stop', function() {
        
    return redirect('/')->with('error', 'Вам необходимо авторизоваться');
})->name('stop');

Route::middleware('auth')->group(function() {
    Route::get('users', 'PagesController@index');

    Route::get('security/{id}', 'PagesController@security');

    Route::get('profile/{id}', 'PagesController@profile');

    Route::get('media/{id}', 'PagesController@media');

    Route::get('status/{id}', 'PagesController@status');

    Route::get('delete/{id}', 'UserController@delete');
});



/*
Route::middleware(['auth', 'admin'])->group(function() {
    Route::get('create_user', 'PagesController@create');
});
*/

Route::get('create', 'PagesController@create');

Route::get('edit/{id}', 'PagesController@edit');


// Auth's routes
Route::post('register', 'UserController@register');

Route::post('login', 'UserController@login');

Route::get('logout', 'UserController@logout');

Route::post('add', 'UserController@add');

Route::post('update', 'UserController@update_info');

Route::post('change_image', 'UserController@change_image');

Route::post('credentials', 'UserController@credentials');

Route::post('set_status', 'UserController@set_status');
