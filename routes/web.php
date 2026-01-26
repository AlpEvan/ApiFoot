<?php

use EvanAlpst\ApiFoot\Controllers\LoginController;
use EvanAlpst\ApiFoot\Controllers\ApiController;

$app->get('/', [ApiController::class, 'show']);
$app->get('/', [ApiController::class, 'validate']);

$app->get('/login', [LoginController::class, 'show']);
$app->post('/login', [LoginController::class, 'validate']);