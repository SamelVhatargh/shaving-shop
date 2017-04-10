<?php
namespace ShavingShop\Repositories;

use ShavingShop\Models\Deliveries\OncePerMonthDelivery;
use ShavingShop\Models\Product;
use ShavingShop\Models\Subscription;
use ShavingShop\Models\SubscriptionPeriod;
use ShavingShop\Models\User;
use ShavingShop\Utils\DateTime;

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
            if (!$this->isValid($row)) {
                continue;
            }
            if ((int)$row['user_id'] === $user->getId()) {
                $subscription = new Subscription(
                    new Product($row['name'], $row['cost']),
                    new SubscriptionPeriod(
                        new DateTime($row['start_date']),
                        $row['end_date'] === null ? null : new DateTime($row['end_date'])
                    ),
                    new OncePerMonthDelivery($row['delivery_day'])
                );

                if ($subscription->isActive()) {
                    return $subscription;
                }
            }
        }
        return null;
    }

    /**
     * Проверяет валидна ли информация о подписке
     * @param array $row информация о подписке
     * @return bool
     */
    private function isValid(array $row): bool
    {
        $fields = ['user_id', 'end_date', 'name', 'cost', 'start_date', 'delivery_day'];
        foreach ($fields as $field) {
            if (!array_key_exists($field, $row)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Сохраняет подписку в хранилище
     * @param Subscription $subscription подписка
     * @return bool результат сохранения
     */
    public function save(Subscription $subscription): bool
    {
        return false;
    }
}
