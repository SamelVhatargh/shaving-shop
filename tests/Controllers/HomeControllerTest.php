<?php
namespace ShavingShop\Tests\Controllers;

use ShavingShop\Controllers\HomeController;
use PHPUnit\Framework\TestCase;

class HomeControllerTest extends TestCase
{

    /**
     * На главной странице должен отображаться заголовок "Сервис продажи
     * бритвенных станков"
     */
    public function testShouldShowShavingShopLabelOnHomePage()
    {
        $controller = new HomeController();
        $shopLabel = 'Сервис продажи бритвенных станков';

        $controller->start();

        $this->expectOutputRegex("/$shopLabel/");
    }
    
}
