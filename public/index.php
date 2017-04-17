<?php
use ShavingShop\Controllers\SubscriptionsController;
use Slim\App;
use Slim\Views\PhpRenderer;
use ShavingShop\Utils\DateTime;

session_start();

function restore(): void
{
    $_SESSION['data']['subscriptions'] = [
        [
            'id' => 1,
            'name' => 'Бритвенный станок',
            'cost' => '1',
            'user_id' => '1',
            'end_date' => null,
            'start_date' => '2017-01-05 12:01:45',
            'delivery_day' => '3',
        ]
    ];
}

if (!isset($_SESSION['data'])) {
    restore();
}

require '../vendor/autoload.php';

if (isset($_GET['test_date'])) {
    DateTime::setCurrentDate($_GET['test_date']);
}

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
$app->get('/history', SubscriptionsController::class . ':history');
$app->get('/clear/{id}', SubscriptionsController::class . ':clear');
$app->get('/restore', SubscriptionsController::class . ':restore');
$app->get('/create', SubscriptionsController::class . ':create');
$app->post('/create', SubscriptionsController::class . ':create');
$app->get('/update', SubscriptionsController::class . ':update');
$app->post('/update', SubscriptionsController::class . ':update');
$app->run();