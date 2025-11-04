<?php

require 'Database.php';
require 'QueryBuilder.php';
require 'ApiController.php';
require 'Router.php';

Database::connect();

header('Content-Type: application/json');

$controller = new ApiController();

$router = new Router();

$router->add('GET', '/users', fn() => $controller->getUsers());

$router->dispatch();