<?php
namespace ShavingShop\Models\Deliveries;

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
}
