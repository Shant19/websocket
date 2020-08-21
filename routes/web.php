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


Route::group( ['middleware' => 'auth' ], function() {
    Route::get('/', 'Chat@index');
    Route::post('/addchat', 'Chat@addChat');
    Route::post('/search', 'Chat@searchUser');
    Route::post('/adduser', 'Chat@addUserToChat');
    Route::post('/addmessage', 'Chat@addMessage');
    Route::post('/getmessages', 'Chat@getMessages');
});

Route::get('/login', "AuthController@index")->name('login');
Route::get('/register', "AuthController@register");
Route::get('/logout', 'AuthController@logout');
Route::post('/login', "AuthController@login");
Route::post('/register', "AuthController@signup");


