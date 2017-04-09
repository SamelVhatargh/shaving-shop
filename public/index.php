<?php
use ShavingShop\Controllers\HomeController;
use Slim\App;
use Slim\Views\PhpRenderer;

require '../vendor/autoload.php';

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$app = new App($configuration);
$container = $app->getContainer();
$container['view'] = function ($container) {
    return new PhpRenderer(__DIR__ . '/../src/views/');
};

$app->get('/', HomeController::class . ':start');
$app->run();