<?php
namespace ShavingShop\Models\Deliveries;

use DateInterval;
use DatePeriod;
use ShavingShop\Utils\DateTime;

/**
 * Доставка два раза в месяц
 */
class TwicePerMonthDelivery implements DeliveryInterface
{

    /**
     * @var int
     */
    private $firstDay;

    /**
     * @var int
     */
    private $secondDay;

    /**
     * TwicePerMonthDelivery constructor.
     * @param int $firstDay
     * @param int $secondDay
     */
    public function __construct(int $firstDay, int $secondDay)
    {
        $this->firstDay = $firstDay;
        $this->secondDay = $secondDay;
    }

    /**
     * Возвращает идентификатор типа доставки
     * @return string
     */
    public function getId(): string
    {
        return 'twicePerMonth';
    }

    /**
     * Возвращает словесное описание доставки
     * @return string
     */
    public function getDescription(): string
    {
        return 'каждый месяц ' . $this->firstDay . '-ого и ' . $this->secondDay . '-ого числа';
    }

    /**
     * Возвращает словесное описание типа доставки
     * @return string
     */
    public function getLabel(): string
    {
        return 'Два раза в месяц';
    }

    /**
     * Возвращает массив с датами доставки в указанном периоде
     * @param DateTime $startDate начало периода
     * @param DateTime $endDate конец периода
     * @return DateTime[]
     */
    public function getDeliveryDates(DateTime $startDate, DateTime $endDate = null): array {
        if ($endDate === null) {
            $endDate = DateTime::now();
        }

        $dates = array_merge(
            $this->returnDatesForDay($this->firstDay, $startDate, $endDate),
            $this->returnDatesForDay($this->secondDay, $startDate, $endDate)
        );
        usort($dates, function($a, $b) {
            return $a <=> $b;
        });
        return $dates;
    }

    /**
     * Возвращает день доставки
     * @return int
     */
    public function getDeliveryDay(): int
    {
        return $this->firstDay;
    }

    /**
     * Возвращает второй день доставки или четность месяца доставки
     * @return int
     */
    public function getSecondDeliveryDayOrMonth(): ?int
    {
        return $this->secondDay;
    }

    /**
     * @param int $day
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return array
     */
    private function returnDatesForDay(int $day, DateTime $startDate, DateTime $endDate): array {
        if ($endDate === null) {
            $endDate = DateTime::now();
        }

        $interval = new DateInterval('P1M');
        $periodStartDate = new DateTime();
        $periodStartDate->setDate(
            $startDate->format('Y'),
            $startDate->format('m'),
            $day
        );
        $periodEndDate = new DateTime();
        $periodEndDate->setDate(
            $endDate->format('Y'),
            $endDate->format('m'),
            $day
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
}
