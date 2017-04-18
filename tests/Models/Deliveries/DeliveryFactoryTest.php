<?php
namespace ShavingShop\Tests\Models\Deliveries;

use ShavingShop\Models\Deliveries\DeliveryFactory;
use PHPUnit\Framework\TestCase;
use ShavingShop\Models\Deliveries\OncePerMonthDelivery;

/**
 * Тест фабрики доставки
 */
class DeliveryFactoryTest extends TestCase
{

    /**
     * Создание OncePerMonthDelivery
     */
    public function testShouldCreateOncePerMonthDeliveryIfSuchTypeWasSpecified()
    {
        $row = [
            'delivery_type' => 'oncePerMonth',
            'delivery_day' => '1',
        ];

        $delivery = DeliveryFactory::createByRow($row);

        $this->assertInstanceOf(OncePerMonthDelivery::class, $delivery);
    }
}

