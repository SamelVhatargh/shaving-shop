<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App();
$app->get('/', function (Request $request, Response $response) {
	echo '<h1>Сервис продажи бритвенных станков</h1>';
});
$app->run();