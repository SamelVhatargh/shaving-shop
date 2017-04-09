<?php
namespace ShavingShop\Tests\Utils;

use ShavingShop\Utils\DateTime;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class DateTimeTest extends TestCase
{

    /**
     * По умолчанию возвращается текущая дата
     */
    public function testNowShouldReturnCurrentDateByDefault()
    {
        $currentDate = (new \DateTime())->format('Y-m-d H:i');

        $now = DateTime::now()->format('Y-m-d H:i');

        $this->assertSame($currentDate, $now);
    }

    /**
     * Если дата установлена то возвращать ее
     */
    public function testNowShouldReturnDateWhichWasSetExplicitly()
    {
        $customDate = '2121-12-05 12:23';
        DateTime::setCurrentDate($customDate);

        $now = DateTime::now()->format('Y-m-d H:i');

        $this->assertSame($customDate, $now);
    }

    /**
     * Если установленную дату сбросили, то возвращать текущую дату
     */
    public function testNowShouldReturnCurrentDateIfExplicitlySetDateWasCleared()
    {
        $currentDate = (new \DateTime())->format('Y-m-d H:i');
        DateTime::setCurrentDate('2121-12-05 12:23');

        DateTime::clearCurrentDate();
        $now = DateTime::now()->format('Y-m-d H:i');

        $this->assertSame($currentDate, $now);
    }
}
