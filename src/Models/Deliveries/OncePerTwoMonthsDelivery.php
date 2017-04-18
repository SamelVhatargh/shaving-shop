<?php
namespace ShavingShop\Models\Deliveries;

use DateInterval;
use DatePeriod;
use ShavingShop\Utils\DateTime;

/**
 * Доставка один раз в два месяца
 */
class OncePerTwoMonthsDelivery implements DeliveryInterface
{
    /**
     * @var int
     */
    private $day;

    /**
     * @var bool
     */
    private $odd;

    /**
     * @param int $day
     * @param bool $odd
     */
    public function __construct(int $day, bool $odd)
    {
        $this->day = $day;
        $this->odd = $odd;
    }

    /**
     * Возвращает идентификатор типа доставки
     * @return string
     */
    public function getId(): string
    {
        return 'oncePerTwoMonth';
    }

    /**
     * Возвращает словесное описание доставки
     * @return string
     */
    public function getDescription(): string
    {
        return 'каждый ' . ($this->odd ? 'нечетный' : 'четный') . ' месяц ' . $this->day . '-ого числа';

    }

    /**
     * Возвращает словесное описание типа доставки
     * @return string
     */
    public function getLabel(): string
    {
        return 'Раз в два месяца';
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

        $interval = new DateInterval('P2M');

        $month = $startDate->format('n');
        if (($this->odd && !($month & 1)) || (!$this->odd && ($month & 1))) {
            $month++;
        }
        $periodStartDate = new DateTime();
        $periodStartDate->setDate(
            $startDate->format('Y'),
            $month,
            $this->day
        );

        $month = $endDate->format('n');
        if (($this->odd && !($month & 1)) || (!$this->odd && ($month & 1))) {
            $month++;
        }
        $periodEndDate = new DateTime();
        $periodEndDate->setDate(
            $endDate->format('Y'),
            $month,
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

    /**
     * Возвращает второй день доставки или четность месяца доставки
     * @return int
     */
    public function getSecondDeliveryDayOrMonth(): ?int
    {
        return $this->odd ? 1 : 2;
    }
}
