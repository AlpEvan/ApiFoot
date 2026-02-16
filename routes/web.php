<?php

use EvanAlpst\ApiFoot\Controllers\LoginController;
use EvanAlpst\ApiFoot\Controllers\ApiController;

use EvanAlpst\ApiFoot\Controllers\RegisterController;


$app->get('/', [ApiController::class, 'show']);
$app->post('/search', [ApiController::class, 'search']);

$app->get('/login', [LoginController::class, 'show']);
$app->post('/login', [LoginController::class, 'validate']);

$app->get('/register', [RegisterController::class, 'show']);
$app->post('/register', [RegisterController::class, 'validate']);