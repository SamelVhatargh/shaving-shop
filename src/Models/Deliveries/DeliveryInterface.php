<?php
namespace ShavingShop\Models\Deliveries;

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
}