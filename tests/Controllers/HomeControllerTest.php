<?php
namespace ShavingShop\Tests\Controllers;

use ShavingShop\Controllers\SubscriptionsController;
use PHPUnit\Framework\TestCase;
use Slim\Container;
use Slim\Views\PhpRenderer;

class HomeControllerTest extends TestCase
{

    /**
     * На главной странице должен отображаться заголовок "Сервис продажи
     * бритвенных станков"
     */
    public function testShouldShowShavingShopLabelOnHomePage()
    {
        $container = new Container();
        $container['view'] = function ($container) {
            return new PhpRenderer(__DIR__ . '/../../src/views/');
        };
        $controller = new SubscriptionsController($container);
        $shopLabel = 'Сервис продажи бритвенных станков';

        $response = $controller->active($container->request, $container->response);
        $response->getBody()->rewind();

        $this->assertContains($shopLabel, $response->getBody()->getContents());
    }
    
}
