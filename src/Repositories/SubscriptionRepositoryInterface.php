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
     * Возвращает все подписки пользователя
     * @param User $user
     * @return Subscription[]
     */
    public function getSubscriptionsForUser(User $user): array;

    /**
     * Сохраняет подписку в хранилище
     * @param Subscription $subscription подписка
     * @return bool результат сохранения
     */
    public function save(Subscription $subscription): bool;

    /**
     * Возвращает подписку по идентификатору
     * @param int $id
     * @return Subscription
     */
    public function getById(int $id): ?Subscription;
}