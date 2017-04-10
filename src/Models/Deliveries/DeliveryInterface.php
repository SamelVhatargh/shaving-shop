<?php
namespace ShavingShop\Models\Deliveries;

use ShavingShop\Utils\DateTime;

/**
 * Информация о доставке
 */
interface DeliveryInterface
{

    /**
     * Возвращает словесное описание доставки
     * @return string
     */
    public function getDescription(): string;


    /**
     * Возвращает массив с датами доставки в указанном периоде
     * @param DateTime $startDate начало периода
     * @param DateTime $endDate конец периода
     * @return DateTime[]
     */
    public function getDeliveryDates(DateTime $startDate, DateTime $endDate = null): array;

    /**
     * Возвращает день доставки
     * @return int
     */
    public function getDeliveryDay(): int;
}