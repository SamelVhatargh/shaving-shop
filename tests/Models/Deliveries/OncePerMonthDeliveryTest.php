<?php
namespace ShavingShop\Tests\Models\Deliveries;

use ShavingShop\Models\Deliveries\OncePerMonthDelivery;
use PHPUnit\Framework\TestCase;
use ShavingShop\Utils\DateTime;

/**
 * Тесты доставки раз в месяц
 */
class OncePerMonthDeliveryTest extends TestCase
{

    protected function setUp()
    {
        parent::setUp();
        DateTime::setCurrentDate('2017-02-07');
    }

    /**
     * Проверка формирования дат доставки при разных периодах и разных днях доставки
     * @dataProvider getDeliveryDatesDataProvider
     * @param int $day
     * @param string $startDate
     * @param string $endDate
     * @param string[] $expectedDates
     */
    public function testGetDeliveryDates($day, $startDate, $endDate, $expectedDates)
    {
        $delivery = new OncePerMonthDelivery($day);

        $dates = $delivery->getDeliveryDates(
            new DateTime($startDate),
            $endDate !== null ? new DateTime($endDate) : null
        );

        $this->assertSame($expectedDates, array_map(function (DateTime $date) {
            return $date->format('Y-m-d');
        }, $dates));
    }

    public function getDeliveryDatesDataProvider()
    {
        return [
            [5, '2017-01-02', '2017-01-07', ['2017-01-05']],
            [5, '2017-01-02', '2017-02-07', ['2017-01-05', '2017-02-05']],
            [1, '2017-01-02', '2017-02-07', ['2017-02-01']],
            [9, '2017-01-02', '2017-02-07', ['2017-01-09']],
            [1, '2017-01-02', '2017-01-07', []],
            [5, '2017-12-02', '2018-01-07', ['2017-12-05', '2018-01-05']],
            [1, '2017-01-02', null, ['2017-02-01']],
        ];
    }

    protected function tearDown()
    {
        parent::tearDown();
        DateTime::clearCurrentDate();
    }
}

