<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix'=> 'api'], function() use ($router) {

	$router->get('emails', 'Api\EmailsController@index');
	$router->get('email/{email}', 'Api\EmailsController@pesquisa');
	$router->get('emails/{id}', 'Api\EmailsController@view');
	// $router->post('emails', 'Api\EmailsController@store');
	// $router->put('emails/{id}', 'Api\EmailsController@update');
	// $router->delete('emails/{id}', 'Api\EmailsController@destroy');


	$router->get('nomes', 'Api\NomesController@index');
	
	
	$router->post('nomes', 'Api\NomesController@pesquisa');
	
	$router->post('enderecos', 'Api\EnderecosController@pesquisa');

	$router->post('telefones', 'Api\TelefonesController@pesquisa');
	
	//$router->get('nome/{nome}', 'Api\NomesController@pesquisa');


});
