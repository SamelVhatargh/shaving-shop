<?php
namespace ShavingShop\Tests\Models\Deliveries;

use ShavingShop\Models\Deliveries\OncePerTwoMonthsDelivery;
use PHPUnit\Framework\TestCase;
use ShavingShop\Utils\DateTime;

/**
 * Тесты доставки два раза в месяц
 */
class OncePerTwoMonthsDeliveryTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        DateTime::setCurrentDate('2017-02-07');
    }

    /**
     * Проверка формирования дат доставки при разных периодах и разных днях доставки
     * @dataProvider getDeliveryDatesDataProvider
     * @param $day
     * @param $odd
     * @param string $startDate
     * @param string $endDate
     * @param string[] $expectedDates
     */
    public function testGetDeliveryDates($day, $odd, $startDate, $endDate, $expectedDates)
    {
        $delivery = new OncePerTwoMonthsDelivery($day, $odd);

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
            [3, true, '2017-01-02', '2017-01-07', ['2017-01-03']],
            [3, false, '2017-01-02', '2017-01-07', []],

            [5, true, '2017-01-02', '2017-02-07', ['2017-01-05']],
            [5, false, '2017-01-02', '2017-02-07', ['2017-02-05']],

            [3, true, '2017-01-02', '2017-04-07', ['2017-01-03', '2017-03-03']],
            [3, false, '2017-01-02', '2017-04-07', ['2017-02-03', '2017-04-03']],

            [5, true, '2017-12-02', '2018-01-07', ['2018-01-05']],
            [3, true, '2017-01-02', null, ['2017-01-03']],
        ];
    }

    protected function tearDown()
    {
        parent::tearDown();
        DateTime::clearCurrentDate();
    }
}

