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


    /**
     * Сохраняет подписку в хранилище
     * @param Subscription $subscription подписка
     * @return bool результат сохранения
     */
    public function save(Subscription $subscription): bool;
}