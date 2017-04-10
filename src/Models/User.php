<?php
namespace ShavingShop\Models;

use ShavingShop\Repositories\SubscriptionRepositoryInterface;
use ShavingShop\Utils\DateTime;

/**
 * Модель пользователя
 */
class User
{
    /**
     * @var SubscriptionRepositoryInterface
     */
    private $subscriptionRepository;
    /**
     * @var int
     */
    private $id;

    /**
     * User constructor.
     * @param int $id
     * @param SubscriptionRepositoryInterface $subscriptionRepository
     */
    public function __construct(
        int $id,
        SubscriptionRepositoryInterface $subscriptionRepository
    )
    {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->id = $id;
    }

    /**
     * Возвращает текущую подписку пользователя
     */
    public function getActiveSubscription(): ?Subscription
    {
        return $this->subscriptionRepository->getActiveSubscriptionsForUser($this);
    }

    /**
     * Возвращает айди пользователя
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Отменяет подписку пользователя
     * @param Subscription $subscription подписка
     */
    public function cancelSubscription(Subscription $subscription)
    {
        $subscription->setEndDate(DateTime::now());
        $this->subscriptionRepository->save($subscription);
    }
}