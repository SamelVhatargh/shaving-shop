<?php
namespace ShavingShop\Models;

use ShavingShop\Repositories\SubscriptionRepositoryInterface;

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
     * User constructor.
     * @param SubscriptionRepositoryInterface $subscriptionRepository
     */
    public function __construct(SubscriptionRepositoryInterface $subscriptionRepository)
    {

        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * Возвращает текущую подписку пользователя
     */
    public function getActiveSubscription(): ?Subscription
    {
        return $this->subscriptionRepository->getActiveSubscriptionsForUser($this);
    }
}