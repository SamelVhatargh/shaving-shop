<?php
namespace ShavingShop\Models\Deliveries;

/**
 * Фабрика вариантов доставки
 */
class DeliveryFactory
{
    public static function createByRow(array $row): ?DeliveryInterface
    {
        return new OncePerMonthDelivery($row['delivery_day']);
    }
}
