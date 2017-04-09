<?php
use ShavingShop\Controllers\SubscriptionsController;
use Slim\App;
use Slim\Views\PhpRenderer;

session_start();

$_SESSION['data']['subscriptions'] = [
    [
        'name' => 'Бритвенный станок',
        'cost' => '1',
        'user_id' => '1',
        'end_date' => null,
        'start_date' => '2017-01-05 12:01:45',
        'delivery_day' => '3',
    ]
];


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

$app->get('/', SubscriptionsController::class . ':active');
$app->run();