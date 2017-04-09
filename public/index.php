<?php
use ShavingShop\Controllers\HomeController;

require '../vendor/autoload.php';

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$app = new \Slim\App($configuration);
$app->get('/', HomeController::class . ':start');
$app->run();