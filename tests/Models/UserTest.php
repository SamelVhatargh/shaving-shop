<?php
namespace ShavingShop\Tests\Models;

use ShavingShop\Models\Subscription;
use ShavingShop\Models\User;
use PHPUnit\Framework\TestCase;
use ShavingShop\Repositories\SubscriptionRepositoryInterface;

class UserTest extends TestCase
{

    /**
     * Получение текущей подписки из репозитория
     */
    public function testGetActiveSubscriptionShouldReturnActiveSubscriptionFromRepository()
    {
        $subscriptionFromRepository = new Subscription();
        $repository = $this->getMockBuilder(SubscriptionRepositoryInterface::class)
            ->getMock();
        $user = new User(1, $repository);
        $repository->expects($this->once())
            ->method('getActiveSubscriptionsForUser')
            ->with($user)
            ->willReturn($subscriptionFromRepository);

        $subscription = $user->getActiveSubscription();

        $this->assertSame($subscriptionFromRepository, $subscription);
    }

    /**
     * Возвращать null если нет активной подписки
     */
    public function testGetActiveSubscriptionShouldReturnNullIfNoSubscriptionsInRepository()
    {
        $repository = $this->getMockBuilder(SubscriptionRepositoryInterface::class)
            ->getMock();
        $user = new User(1, $repository);
        $repository->expects($this->once())
            ->method('getActiveSubscriptionsForUser')
            ->with($user)
            ->willReturn(null);

        $subscription = $user->getActiveSubscription();

        $this->assertNull($subscription);
    }
}
