<?php

$router->get('/', function(){
	return view('app');
});

$router->post('/buscar-cedula','DataController@search');