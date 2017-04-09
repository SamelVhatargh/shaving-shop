<?php
namespace ShavingShop\Repositories;

use ShavingShop\Models\Product;
use ShavingShop\Models\Subscription;
use ShavingShop\Models\User;

/**
 * Репозиторий для получения инфы по подпискам из массива
 */
class ArraySubscriptionsRepository implements SubscriptionRepositoryInterface
{
    /**
     * @var array
     */
    private $data;

    /**
     * ArraySubscriptionsRepository constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Возвращает активную подписку для пользователя
     * @param User $user
     * @return Subscription
     */
    public function getActiveSubscriptionsForUser(User $user): ?Subscription
    {
        foreach ($this->data as $row) {
            if (array_key_exists('user_id', $row)
                && (int)$row['user_id'] === $user->getId()) {
                if (array_key_exists('end_date', $row)
                    && $row['end_date'] === null) {
                    return new Subscription(new Product(
                        $row['name'],
                        $row['cost']
                    ));
                }
            }
        }
        return null;
    }
}
