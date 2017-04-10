<?php
namespace ShavingShop\Models\Deliveries;

use DateInterval;
use DatePeriod;
use ShavingShop\Utils\DateTime;

/**
 * Доставка один раз в месяц
 */
class OncePerMonthDelivery implements DeliveryInterface
{

    private $day;

    /**
     * OncePerMonthDelivery constructor.
     * @param int $day
     */
    public function __construct(int $day)
    {
        $this->day = $day;
    }

    /**
     * Возвращает словесное описание доставки
     * @return string
     */
    public function getDescription(): string
    {
        return 'каждый месяц ' . $this->day . '-ого числа';
    }

    /**
     * Возвращает массив с датами доставки в указанном периоде
     * @param DateTime $startDate начало периода
     * @param DateTime $endDate конец периода
     * @return DateTime[]
     */
    public function getDeliveryDates(DateTime $startDate, DateTime $endDate = null): array
    {
        if ($endDate === null) {
            $endDate = DateTime::now();
        }

        $interval = new DateInterval('P1M');
        $periodStartDate = new DateTime();
        $periodStartDate->setDate(
            $startDate->format('Y'),
            $startDate->format('m'),
            $this->day
        );
        $periodEndDate = new DateTime();
        $periodEndDate->setDate(
            $endDate->format('Y'),
            $endDate->format('m'),
            $this->day
        )->add($interval);

        $period = new DatePeriod($periodStartDate, $interval, $periodEndDate);

        $dates = [];
        foreach ($period as $date) {
            if ($startDate < $date && $date < $endDate) {
                $dates[] = $date;
            }
        }
        return $dates;
    }

    /**
     * Возвращает день доставки
     * @return int
     */
    public function getDeliveryDay(): int
    {
        return $this->day;
    }
}
