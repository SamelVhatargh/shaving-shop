<?php
use ShavingShop\Controllers\HomeController;
use Slim\App;
use Slim\Views\PhpRenderer;

session_start();

if (!isset($_SESSION['data'])) {
    $_SESSION['data']['subscriptions'] = [
        [
            'name' => 'Бритвенный станок',
            'cost' => '1',
            'user_id' => '1',
            'end_date' => null,
        ]
    ];
}


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