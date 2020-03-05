<?php

$router->get('/', function() use ($router) {
	return view('home');
});

$router->post('/buscar-cedula','DataController@search');
