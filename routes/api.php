<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken($request->device_name)->plainTextToken;
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/agenda', 'AgendaController@index')
    ->name('agenda.index');

Route::middleware('auth:sanctum')->get('/agenda/listar', 'AgendaController@index')
    ->name('agenda.index.listar');
Route::middleware('auth:sanctum')->post('/agenda', 'AgendaController@store')
    ->name('agenda.store');
Route::middleware('auth:sanctum')->post('/agenda/{id}/update', 'AgendaController@update')
    ->name('agenda.update');
Route::middleware('auth:sanctum')->get('/agenda/{id}/update', 'AgendaController@edit') // load de registro para atualizar
->name('agenda.edit');

Route::middleware('auth:sanctum')->get('/agenda/{id}/destroy', 'AgendaController@destroy')
    ->name('agenda.destroy');


//
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
