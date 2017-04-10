<?php
namespace ShavingShop\Tests\Models;

use DateInterval;
use ShavingShop\Models\Deliveries\OncePerMonthDelivery;
use ShavingShop\Models\Product;
use ShavingShop\Models\Subscription;
use PHPUnit\Framework\TestCase;
use ShavingShop\Utils\DateTime;

/**
 * Тесты методов подписки
 */
class SubscriptionTest extends TestCase
{

    /**
     * Проверка активности подписки
     * @dataProvider isActiveDataProvider
     */
    public function testIsActive($startDate, $endDate, $isActive)
    {
        $subscription = new Subscription(
            new Product('Кружка', 100),
            $startDate,
            new OncePerMonthDelivery(1),
            $endDate
        );

        $this->assertSame($isActive, $subscription->isActive());
    }


    public function isActiveDataProvider()
    {
        $today = DateTime::now();
        $yesterday = DateTime::now()->sub(new DateInterval('P1D'));
        $twoDaysAgo = DateTime::now()->sub(new DateInterval('P2D'));
        $tomorrow = DateTime::now()->add(new DateInterval('P1D'));
        $twoDaysInFuture = DateTime::now()->add(new DateInterval('P2D'));
        return [
            [$yesterday, null, true],
            [$yesterday, $tomorrow, true],
            [$twoDaysAgo, $yesterday, false],
            [$tomorrow, $twoDaysInFuture, false],
            [$tomorrow, null, false],
            [$yesterday, $today, false],
        ];
    }
}

