<?php
namespace ShavingShop\Tests\Models\Deliveries;

use ShavingShop\Models\Deliveries\DeliveryFactory;
use PHPUnit\Framework\TestCase;
use ShavingShop\Models\Deliveries\OncePerMonthDelivery;
use ShavingShop\Models\Deliveries\OncePerTwoMonthsDelivery;
use ShavingShop\Models\Deliveries\TwicePerMonthDelivery;

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

    /**
     * Создание TwicePerMonthDelivery
     */
    public function testShouldCreateTwicePerMonthDeliveryIfSuchTypeWasSpecified()
    {
        $row = [
            'delivery_type' => 'twicePerMonth',
            'delivery_day' => '1',
            'delivery_second_day_or_month' => '2',
        ];

        $delivery = DeliveryFactory::createByRow($row);

        $this->assertInstanceOf(TwicePerMonthDelivery::class, $delivery);
    }

    /**
     * Создание OncePerTwoMonthDelivery
     */
    public function testShouldCreateOncePerTwoMonthDeliveryIfSuchTypeWasSpecified()
    {
        $row = [
            'delivery_type' => 'oncePerTwoMonth',
            'delivery_day' => '1',
            'delivery_second_day_or_month' => '3',
        ];

        $delivery = DeliveryFactory::createByRow($row);

        $this->assertInstanceOf(OncePerTwoMonthsDelivery::class, $delivery);
    }
}

