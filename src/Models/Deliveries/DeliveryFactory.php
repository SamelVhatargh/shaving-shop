<?php
namespace ShavingShop\Models\Deliveries;

/**
 * Фабрика вариантов доставки
 */
class DeliveryFactory
{
    public static function createByRow(array $row): ?DeliveryInterface
    {
        if ($row['delivery_type'] === 'twicePerMonth') {
            return new TwicePerMonthDelivery(
                $row['delivery_day'],
                $row['delivery_second_day_or_month']
            );
        }
        if ($row['delivery_type'] === 'oncePerTwoMonth') {
            return new OncePerTwoMonthsDelivery(
                $row['delivery_day'],
                $row['delivery_second_day_or_month'] & 1
            );
        }
        return new OncePerMonthDelivery($row['delivery_day']);
    }
}
