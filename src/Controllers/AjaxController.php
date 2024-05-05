<?php

require_once '../../vendor/autoload.php';

use App\Controllers\ContatoController;
use App\Controllers\PessoaController;

session_start();

$path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';

$controller;
switch ($path) {
    case '/pessoa':
        $controller = new PessoaController();
        break;
    case '/contato':
        $controller = new ContatoController();
        break;
    default:
        header("HTTP/1.1 404 Not Found");
        exit();
}
$controller->handleRequest();
