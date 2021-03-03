<?php

$router->get('api/users', 'UsersController@index');
$router->post('api/users', 'UsersController@create');
$router->delete('api/users', 'UsersController@delete');
$router->delete('api/users/username', 'UsersController@deleteByUsername');