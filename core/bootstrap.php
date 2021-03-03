<?php


use App\Core\App;
use App\Core\AppRouter;
use App\Core\Database\Connection;
use App\Core\Database\QueryBuilder;

App::bind('config', require 'config.php');
App::bind('QueryBuilder', new QueryBuilder(
    new Connection(App::get('config')['database'])
));
App::bind('router', new AppRouter(
    App::get('config')['routes']
));