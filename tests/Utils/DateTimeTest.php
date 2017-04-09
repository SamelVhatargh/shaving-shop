<?php
namespace ShavingShop\Tests\Utils;

use ShavingShop\Utils\DateTime;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class DateTimeTest extends TestCase
{
    const FORMAT = 'Y-m-d H:i';

    /**
     * По умолчанию возвращается текущая дата
     */
    public function testNowShouldReturnCurrentDateByDefault()
    {
        $currentDate = (new \DateTime())->format(self::FORMAT);

        $now = DateTime::now()->format(self::FORMAT);

        $this->assertSame($currentDate, $now);
    }

    /**
     * Если дата установлена то возвращать ее
     */
    public function testNowShouldReturnDateWhichWasSetExplicitly()
    {
        $customDate = '2017-12-05 12:23';
        DateTime::setCurrentDate($customDate);

        $now = DateTime::now()->format(self::FORMAT);

        $this->assertSame($customDate, $now);
    }

    /**
     * Если установленную дату сбросили, то возвращать текущую дату
     */
    public function testNowShouldReturnCurrentDateIfExplicitlySetDateWasCleared()
    {
        $currentDate = (new \DateTime())->format(self::FORMAT);
        DateTime::setCurrentDate('2017-12-05 12:23');

        DateTime::clearCurrentDate();
        $now = DateTime::now()->format(self::FORMAT);

        $this->assertSame($currentDate, $now);
    }
}
