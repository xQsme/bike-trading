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
//Guest
Auth::routes();
Route::get('register/{userid}/verify/{token}', 'Auth\RegisterController@verify');
Route::get('/', 'GuestController@index')->name('home');
Route::get('/lista/{categoria}/{subcategoria}', 'GuestController@lista')->name('lista');
Route::post('/lista/{categoria}/{subcategoria}', 'GuestController@listaOrdenada')->name('listaOrdenada');
Route::post('/pesquisar', 'GuestController@pesquisar')->name('pesquisar');
Route::get('/anuncio/{item}', 'GuestController@anuncio')->name('anuncio');
Route::get('/perfil/{user}', 'GuestController@perfil')->name('perfil');
Route::get('/sobre', 'GuestController@sobre')->name('sobre');
Route::get('/condicoes', 'GuestController@condicoes')->name('condicoes');

//Auth
Route::get('/notificacoes', 'AuthController@mensagens')->name('mensagens');
Route::get('/criar', 'AuthController@criar')->name('criar');
Route::post('/criar', 'AuthController@store')->name('store');
Route::post('/perfil/{user}', 'AuthController@comment')->name('comment');
Route::post('/perfil/{user}/responder/{comentario}', 'AuthController@responder')->name('responder');
Route::get('/editarperfil', 'AuthController@editarPerfil')->name('editarPerfil');
Route::post('/editarperfil', 'AuthController@updatePerfil')->name('updatePerfil');
Route::put('/editarperfil', 'AuthController@updatePassword')->name('updatePassword');

//Self
Route::get('/editar/{item}', 'SelfController@editar')->name('editar')->middleware('can:self,item');
Route::get('/bump/{item}', 'SelfController@bump')->name('bump')->middleware('can:self,item');
Route::post('/editar/{item}', 'SelfController@storeEdit')->name('storeEdit')->middleware('can:self,item');
Route::get('/apagar/{item}', 'SelfController@apagar')->name('apagar')->middleware('can:self,item');
Route::get('/apagarnotificacao/{mensagem}', 'SelfController@apagarmensagem')->name('apagarmensagem')->middleware('can:self,mensagem');

//Admin
Route::get('/aprovar/{item}', 'AdminController@aprovar')->name('aprovar');
Route::post('/rejeitar/{item}', 'AdminController@rejeitar')->name('rejeitar');
Route::get('/remover/{item}', 'AdminController@remover')->name('remover');
Route::get('/pendentes', 'AdminController@pendentes')->name('pendentes');
Route::get('/rejeitados', 'AdminController@rejeitados')->name('rejeitados');
Route::get('/removerComentario/{comentario}', 'AdminController@removerComentario')->name('removerComentario');