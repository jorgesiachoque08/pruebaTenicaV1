<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([

    'middleware' => 'api'

], function ($router) {

    Route::post('/auth/login', 'auth\UserController@login');
    Route::post('/auth/registrar', 'auth\UserController@registrar');

});


Route::middleware('jwt.verify','solicitante')->group(function () {

    Route::post('/solicitante/subscribirse', ['as' => 'solicitante.subscribirse', 'uses' => 'perfil\SolicitanteController@subscribirse']);
    Route::post('/solicitante/apartarCupo', ['as' => 'solicitante.apartarCupo', 'uses' => 'perfil\SolicitanteController@apartarCupo']);
    Route::get('/solicitante/listarCuposCita/{codCita}', ['as' => 'solicitante.listarCuposCita', 'uses' => 'perfil\SolicitanteController@listarCuposCita']);
    Route::get('/solicitante/listarPrestadores', ['as' => 'solicitante.listarPrestadores', 'uses' => 'perfil\SolicitanteController@listarPrestadores']);
    Route::get('/solicitante/listarCitas', ['as' => 'solicitante.listarCitas', 'uses' => 'perfil\SolicitanteController@listarCitas']);

});

Route::middleware('jwt.verify')->group(function () {

    Route::post('/auth/asignarPerfil', ['as' => 'auth.asignarPerfil', 'uses' => 'auth\UserController@asignarPerfil']);

});

Route::middleware('jwt.verify','prestador')->group(function () {

    Route::post('/prestador/crearCita', ['as' => 'prestador.crearCita', 'uses' => 'perfil\PrestadorController@crearCita']);
    Route::get('/prestador/listarCuposCita/{codCita}', ['as' => 'prestador.listarCuposCita', 'uses' => 'perfil\PrestadorController@listarCuposCita']);
    Route::get('/prestador/listarCitas', ['as' => 'prestador.listarCitas', 'uses' => 'perfil\PrestadorController@listarCitas']);
});


    
