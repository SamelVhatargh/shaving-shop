<?php
namespace ShavingShop\Tests\Repositories;

use DateInterval;
use ShavingShop\Models\User;
use ShavingShop\Repositories\ArraySubscriptionsRepository;
use PHPUnit\Framework\TestCase;
use ShavingShop\Utils\DateTime;

/**
 * Тесты репозитория массива с подписками
 */
class ArraySubscriptionsRepositoryTest extends TestCase
{

    /**
     * getActiveSubscriptionsForUser возвращает null если у пользователя нет активных подписок
     */
    public function testGetActiveSubscriptionsForUserShouldReturnNullIfUserHasNoActiveSubscriptions() {
        $repWithExpiredSubscriptions = new ArraySubscriptionsRepository([
            [
                'user_id' => '1',
                'end_date' => $this->getYesterday()->format('Y-m-d H:i:s'),
            ],
        ]);
        $user = new User(1, $repWithExpiredSubscriptions);

        $subscription = $repWithExpiredSubscriptions->getActiveSubscriptionsForUser($user);

        $this->assertNull($subscription);
    }

    /**
     * getActiveSubscriptionsForUser возвращает активную подписку если у пользователя есть подписка
     */
    public function testGetActiveSubscriptionsForUserShouldReturnActiveSubscriptionIfUserHasActiveSubscription() {
        $repWithActiveSubscriptions = new ArraySubscriptionsRepository([
            [
                'name' => 'Кружка',
                'cost' => 100,
                'user_id' => '1',
                'end_date' => null,
            ],
        ]);
        $user = new User(1, $repWithActiveSubscriptions);

        $subscription = $repWithActiveSubscriptions->getActiveSubscriptionsForUser($user);

        $this->assertTrue($subscription->isActive());
    }

    /**
     * getActiveSubscriptionsForUser возвращает null если передан некорректный массив с подписками
     */
    public function testGetActiveSubscriptionsForUserShouldReturnNullIfSubscriptionsArrayIsInvalid() {
        $repWithInvalidArray = new ArraySubscriptionsRepository([[
            [
                'endDate' => null,
            ],
        ]]);
        $user = new User(1, $repWithInvalidArray);

        $subscription = $repWithInvalidArray->getActiveSubscriptionsForUser($user);

        $this->assertNull($subscription);
    }

    /**
     * getActiveSubscriptionsForUser возвращает null если в массиве есть
     * активные подписки, но они для других пользователей
     */
    public function testGetActiveSubscriptionsForUserShouldReturnNullIfArrayContainsActiveSubscriptionsButNotForThisUser() {
        $repWithInvalidArray = new ArraySubscriptionsRepository([
            [
                'user_id' => '2',
                'end_date' => null,
            ],
        ]);
        $user = new User(1, $repWithInvalidArray);

        $subscription = $repWithInvalidArray->getActiveSubscriptionsForUser($user);

        $this->assertNull($subscription);
    }

    /**
     * Информация о продукте должна возвращаться вместе с подпиской
     */
    public function testGetActiveSubscriptionsForUserShouldReturnSubscriptionWithProductInfo() {
        $productName = 'Кружка';
        $productCost = 100;
        $repWithInvalidArray = new ArraySubscriptionsRepository([
            [
                'name' => 'Кружка',
                'cost' => 100,
                'user_id' => '1',
                'end_date' => null,
            ],
        ]);
        $user = new User(1, $repWithInvalidArray);

        $subscription = $repWithInvalidArray->getActiveSubscriptionsForUser($user);
        $subscriptionProduct = $subscription->getProduct();

        $this->assertSame($productName, $subscriptionProduct->name);
        $this->assertSame($productCost, $subscriptionProduct->cost);
    }

    /**
     * Возвращает вчерашнюю дату
     * @return DateTime
     */
    private function getYesterday()
    {
        return DateTime::now()->sub(new DateInterval('P1D'));
    }
}

