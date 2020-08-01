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
    return redirect('/books');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('books', 'BookController')->middleware('auth');
Route::middleware('auth:api')->get('/books/show/{id}', 'BookController@show')->where(['id' => '[0-9]+'])->name('books.view');
Route::middleware('auth:api')->get('/books/filter/{status}', 'BookController@filter')->where(['status' => '[A-Za-z ]+'])->name('books.filter');
