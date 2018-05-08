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


Auth::routes();

Route::get('/', function () {
    return redirect('login');
});

Route::get('/home', 'HomeController@index')->middleware(['auth'])->name('home');

Route::prefix('saml')->group(function () {
    Route::get('consume', 'SamlController@consumeRequest')->name('consume');
    Route::get('proceed-connexion', 'SamlController@proceedConnexion')->middleware(['auth'])->name('proceedConnexion');
});

Route::namespace('Admin')->prefix('admin')->middleware(['auth'])->group(function () {
    Route::resource('client', 'ClientController');
    Route::resource('user', 'UserController', ['except' => ['store', 'create']]);
});
