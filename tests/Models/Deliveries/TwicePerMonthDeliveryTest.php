<?php
namespace ShavingShop\Tests\Models\Deliveries;

use ShavingShop\Models\Deliveries\TwicePerMonthDelivery;
use PHPUnit\Framework\TestCase;
use ShavingShop\Utils\DateTime;

/**
 * Тесты доставки два раза в месяц
 */
class TwicePerMonthDeliveryTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        DateTime::setCurrentDate('2017-02-07');
    }

    /**
     * Проверка формирования дат доставки при разных периодах и разных днях доставки
     * @dataProvider getDeliveryDatesDataProvider
     * @param $firstDay
     * @param $secondDay
     * @param string $startDate
     * @param string $endDate
     * @param string[] $expectedDates
     */
    public function testGetDeliveryDates($firstDay, $secondDay, $startDate, $endDate, $expectedDates)
    {
        $delivery = new TwicePerMonthDelivery($firstDay, $secondDay);

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
            [3, 5, '2017-01-02', '2017-01-07', ['2017-01-03', '2017-01-05']],
            [5, 8, '2017-01-02', '2017-01-07', ['2017-01-05']],
            [1, 3, '2017-01-02', '2017-01-07', ['2017-01-03']],
            [1, 8, '2017-01-02', '2017-01-07', []],

            [3, 5, '2017-01-02', '2017-02-07', ['2017-01-03', '2017-01-05', '2017-02-03', '2017-02-05']],
            [5, 8, '2017-01-02', '2017-02-07', ['2017-01-05', '2017-01-08', '2017-02-05']],
            [1, 3, '2017-01-02', '2017-02-07', ['2017-01-03', '2017-02-01', '2017-02-03']],
            [1, 8, '2017-01-02', '2017-02-07', ['2017-01-08', '2017-02-01']],

            [5, 8, '2017-12-02', '2018-01-07', ['2017-12-05', '2017-12-08', '2018-01-05']],
            [1, 8, '2017-01-02', null, ['2017-01-08', '2017-02-01']],
        ];
    }

    protected function tearDown()
    {
        parent::tearDown();
        DateTime::clearCurrentDate();
    }
}

