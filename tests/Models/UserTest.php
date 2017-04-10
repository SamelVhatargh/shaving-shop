<?php
namespace ShavingShop\Tests\Models;

use ShavingShop\Models\Deliveries\OncePerMonthDelivery;
use ShavingShop\Models\Product;
use ShavingShop\Models\Subscription;
use ShavingShop\Models\SubscriptionPeriod;
use ShavingShop\Models\User;
use PHPUnit\Framework\TestCase;
use ShavingShop\Repositories\SubscriptionRepositoryInterface;
use ShavingShop\Utils\DateTime;

class UserTest extends TestCase
{

    /**
     * Получение текущей подписки из репозитория
     */
    public function testGetActiveSubscriptionShouldReturnActiveSubscriptionFromRepository()
    {
        $subscriptionFromRepository = $this->createSubscription();
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

    /**
     * Получение всех подписок из репозитория
     */
    public function testGetSubscriptionsShouldReturnSubscriptionsArrayFromRepository()
    {
        $subscriptionFromRepository = $this->createSubscription();
        $repository = $this->getMockBuilder(SubscriptionRepositoryInterface::class)
            ->getMock();
        $user = new User(1, $repository);
        $repository->expects($this->once())
            ->method('getSubscriptionsForUser')
            ->with($user)
            ->willReturn([$subscriptionFromRepository]);

        $subscriptions = $user->getSubscriptions();

        $this->assertSame([$subscriptionFromRepository], $subscriptions);
    }

    /**
     * Возвращать null если нет активной подписки
     */
    public function testGetSubscriptionsShouldReturnEmptyArrayIfNoSubscriptionsInRepository()
    {
        $repository = $this->getMockBuilder(SubscriptionRepositoryInterface::class)
            ->getMock();
        $user = new User(1, $repository);
        $repository->expects($this->once())
            ->method('getSubscriptionsForUser')
            ->with($user)
            ->willReturn([]);

        $subscriptions = $user->getSubscriptions();

        $this->assertCount(0, $subscriptions);
    }

    /**
     * После отмены подписка должна стать неактивной
     */
    public function testCancelSubscriptionShouldMakeSubscriptionInactive()
    {
        $subscription = $this->createSubscription();
        $repository = $this->getMockBuilder(SubscriptionRepositoryInterface::class)
            ->getMock();
        $user = new User(1, $repository);

        $user->cancelSubscription($subscription);

        $this->assertFalse($subscription->isActive());
    }

    /**
     * После отмена инфа об отмене сохраняется в хранилище
     */
    public function testCancelSubscriptionShouldSaveStateInRepository()
    {
        $subscription = $this->createSubscription();
        $repository = $this->getMockBuilder(SubscriptionRepositoryInterface::class)
            ->getMock();
        $user = new User(1, $repository);

        $repository->expects($this->once())
            ->method('save')
            ->with($subscription);

        $user->cancelSubscription($subscription);
    }

    /**
     * Возвращать базовую подписку для тестов
     * @return Subscription
     */
    private function createSubscription(): Subscription
    {
        return new Subscription(
            1, new Product('Кружка', 100),
            new SubscriptionPeriod(new DateTime()), new OncePerMonthDelivery(1),
            1
        );
    }
}
