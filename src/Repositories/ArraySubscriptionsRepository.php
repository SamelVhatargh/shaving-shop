<?php
namespace ShavingShop\Repositories;

use ShavingShop\Models\Product;
use ShavingShop\Models\Subscription;
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
                if ($row['end_date'] === null) {
                    return new Subscription(
                        new Product($row['name'], $row['cost']),
                        new DateTime($row['start_date'])
                    );
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
        $fields = ['user_id', 'end_date', 'name', 'cost', 'start_date'];
        foreach ($fields as $field) {
            if (!array_key_exists($field, $row)) {
                return false;
            }
        }
        return true;
    }
}
