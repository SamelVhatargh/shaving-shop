<?php
namespace ShavingShop\Repositories;

use ShavingShop\Models\Subscription;
use ShavingShop\Models\SubscriptionFactory;
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
            if (!$this->isValid($row)) {
                continue;
            }
            if ((int)$row['user_id'] === $user->getId()) {
                $subscription = SubscriptionFactory::createByRow($row);
                if ($subscription->isActive()) {
                    return $subscription;
                }
            }
        }
        return null;
    }

    /**
     * Возвращает подписку по идентификатору
     * @param int $id
     * @return Subscription
     */
    public function getById(int $id): ?Subscription
    {
        foreach ($this->data as $row) {
            if ((int)$row['id'] === $id) {
                return SubscriptionFactory::createByRow($row);
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
        $fields = ['id', 'user_id', 'end_date', 'name', 'cost', 'start_date', 'delivery_day'];
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
        if ($subscription->getId() === null) {
            $maxId = 0;
            foreach ($this->data as $row) {
                if ((int)$row['id'] > $maxId) {
                    $maxId = (int)$row['id'];
                }
            }
            $row = $this->convertSubscriptionToRow($subscription);
            $row['id'] = $maxId + 1;
            $this->data[] = $row;
        }

        foreach ($this->data as &$row) {
            if ((int)$row['id'] === $subscription->getId()) {
                $row = $this->convertSubscriptionToRow($subscription);
                return true;
            }
        }

        return false;
    }

    /**
     * Возвращает последнюю версию данных в массиве
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Возвращает все подписки пользователя
     * @param User $user
     * @return Subscription[]
     */
    public function getSubscriptionsForUser(User $user): array
    {
        $result = [];
        foreach ($this->data as $row) {
            if (!$this->isValid($row)) {
                continue;
            }
            if ((int)$row['user_id'] === $user->getId()) {
                $result[] = SubscriptionFactory::createByRow($row);
            }
        }
        return $result;
    }

    /**
     * @param Subscription $subscription
     * @return array
     */
    private function convertSubscriptionToRow(Subscription $subscription): array
    {
        $endDate = $subscription->getPeriod()->getEndDate();
        $row = [
            'id' => $subscription->getId(),
            'user_id' => $subscription->getUserId(),
            'end_date' => $endDate === null ? null : $endDate->format('Y-m-d H:i:s'),
            'name' => $subscription->getProduct()->name,
            'cost' => $subscription->getProduct()->cost,
            'start_date' => $subscription->getPeriod()->getStartDate()->format('Y-m-d H:i:s'),
            'delivery_day' => $subscription->getDelivery()->getDeliveryDay(),
        ];
        return $row;
    }
}
