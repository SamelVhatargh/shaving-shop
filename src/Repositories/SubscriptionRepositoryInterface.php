<?php
namespace ShavingShop\Repositories;

use ShavingShop\Models\Subscription;
use ShavingShop\Models\User;

/**
 * Интерфейс для хранилищ с информацией о подписках
 */
interface SubscriptionRepositoryInterface
{

    /**
     * Возвращает активную подписку для пользователя
     * @param User $user
     * @return Subscription
     */
    public function getActiveSubscriptionsForUser(User $user): ?Subscription;
}