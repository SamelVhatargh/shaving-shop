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
                'end_date' => $this->getYesterday()->format('Y-m-d H:i:s'),
            ],
        ]);
        $user = new User($repWithExpiredSubscriptions);

        $subscription = $repWithExpiredSubscriptions->getActiveSubscriptionsForUser($user);

        $this->assertNull($subscription);
    }

    /**
     * getActiveSubscriptionsForUser возвращает активную подписку если у пользователя есть подписка
     */
    public function testGetActiveSubscriptionsForUserShouldReturnActiveSubscriptionIfUserHasActiveSubscription() {
        $repWithActiveSubscriptions = new ArraySubscriptionsRepository([
            [
                'end_date' => null,
            ],
        ]);
        $user = new User($repWithActiveSubscriptions);

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
        $user = new User($repWithInvalidArray);

        $subscription = $repWithInvalidArray->getActiveSubscriptionsForUser($user);

        $this->assertNull($subscription);
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

