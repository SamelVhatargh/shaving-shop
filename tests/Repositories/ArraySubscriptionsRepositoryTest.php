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
        list($repWithExpiredSubscriptions, $user) = $this->getRepAndUser([
            'end_date' => $this->getYesterday()->format('Y-m-d H:i:s'),
        ]);

        $subscription = $repWithExpiredSubscriptions->getActiveSubscriptionsForUser($user);

        $this->assertNull($subscription);
    }

    /**
     * getActiveSubscriptionsForUser возвращает активную подписку если у пользователя есть подписка
     */
    public function testGetActiveSubscriptionsForUserShouldReturnActiveSubscriptionIfUserHasActiveSubscription() {
        list($repWithActiveSubscriptions, $user) = $this->getRepAndUser([
            'end_date' => null,
        ]);

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
        list($repWithSubsFromAnotherUser, $user) = $this->getRepAndUser([
            'user_id' => '2',
        ], 1);

        $subscription = $repWithSubsFromAnotherUser->getActiveSubscriptionsForUser($user);

        $this->assertNull($subscription);
    }

    /**
     * Информация о продукте должна возвращаться вместе с подпиской
     */
    public function testGetActiveSubscriptionsForUserShouldReturnSubscriptionWithProductInfo() {
        $productName = 'Кружка';
        $productCost = 100;
        list($repository, $user) = $this->getRepAndUser([
            'name' => $productName,
            'cost' => $productCost,
        ]);

        $subscription = $repository->getActiveSubscriptionsForUser($user);
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

    /**
     * Возвращает репозиторий и модель пользователя с данными по умолчанию
     * @param array $row новые данные для возвращаемой подписки
     * @param int $userId айди возвращаемого пользователя
     * @return array
     */
    private function getRepAndUser(array $row = [], int $userId = 1): array
    {
        $row = array_merge([
            'name' => 'Кружка',
            'cost' => 100,
            'user_id' => '1',
            'end_date' => null,
            'start_date' => '2017-01-05 12:01:45',
        ], $row);
        $rep = new ArraySubscriptionsRepository([$row]);
        $user = new User($userId, $rep);
        return array($rep, $user);
    }
}

