<?php
namespace ShavingShop\Models;

use ShavingShop\Models\Deliveries\DeliveryFactory;
use ShavingShop\Utils\DateTime;

/**
 * Создает объекты подписок
 */
class SubscriptionFactory
{
    /**
     * @param array $row
     * @return Subscription
     */
    public static function createByRow(array $row): Subscription
    {
        return new Subscription(
            $row['id'], new Product($row['name'], $row['cost']),
            new SubscriptionPeriod(
                new DateTime($row['start_date']),
                $row['end_date'] === null ? null : new DateTime($row['end_date'])
            ),
            DeliveryFactory::createByRow($row),
            $row['user_id']
        );
    }
}
