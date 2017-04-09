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
        $user = new User($repository);
        $repository->expects($this->once())
            ->method('getActiveSubscriptionsForUser')
            ->with($user)
            ->willReturn($subscriptionFromRepository);

        $subscription = $user->getActiveSubscription();

        $this->assertSame($subscriptionFromRepository, $subscription);
    }
}
