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
    return view('frontend');
});

//Route::get('/agenda', 'AgendaController@index')
//    ->name('agenda.index');
//
//Route::get('/agenda/listar', 'AgendaController@index')
//    ->name('agenda.index.listar');
//Route::post('/agenda', 'AgendaController@store')
//    ->name('agenda.store');
//Route::post('/agenda/{id}/update', 'AgendaController@update')
//    ->name('agenda.update');
//Route::get('/agenda/{id}/update', 'AgendaController@edit') // load de registro para atualizar
//->name('agenda.edit');
//
//Route::get('/agenda/{id}/destroy', 'AgendaController@destroy')
//    ->name('agenda.destroy');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
