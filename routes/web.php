<?php

$router->get('/', 'DataController@index');
$router->get('/{any}', 'DataController@index');
$router->post('/buscar-cedula','DataController@search');