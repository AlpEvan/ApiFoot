<?php

use EvanAlpst\ApiFoot\Controllers\LoginController;
use EvanAlpst\ApiFoot\Controllers\ApiController;

$app->get('/', [ApiController::class, 'show']);
$app->post('/', [ApiController::class, 'validate']);

$app->get('/login', [LoginController::class, 'show']);
$app->post('/login', [LoginController::class, 'validate']);

$app->get('/register', [RegisterController::class, 'show']);
$app->get('/register', [RegisterController::class, 'validate']);