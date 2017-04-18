<?php
namespace ShavingShop\Tests\Repositories;

use DateInterval;
use ShavingShop\Models\Subscription;
use ShavingShop\Models\SubscriptionFactory;
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
     * Сохранение информации о подписке
     */
    public function testSaveShouldStoreNewInfoInArray()
    {
        $cupSubscriptionData = $this->getCupSubscriptionData();
        $data = [$cupSubscriptionData];
        $subscription = SubscriptionFactory::createByRow($cupSubscriptionData);
        $rep = new ArraySubscriptionsRepository($data);
        $newEndDate = '2015-03-03 20:20:20';

        $subscription->setEndDate(new DateTime($newEndDate));
        $rep->save($subscription);

        $this->assertSame($newEndDate, $rep->getData()[0]['end_date']);
    }

    /**
     * Получение подписки по айди
     */
    public function testGetByIdShouldReturnSubscriptionWithRequestedId()
    {
        list($rep, $user) = $this->getRepAndUser(['id' => '2']);

        $subscription = $rep->getById(2);

        $this->assertSame(2, $subscription->getId());
    }

    /**
     * getSubscriptionsForUser() должен возвращать подписки пользователя
     */
    public function testGetSubscriptionsForUserShouldReturnUserSubscriptions()
    {
        $cupSubscriptionData = $this->getCupSubscriptionData();
        $data = [$cupSubscriptionData];
        $cupSubscriptionData['user_id'] = '2';
        $data[] = $cupSubscriptionData;
        $rep = new ArraySubscriptionsRepository($data);
        $user = new User(1, $rep);

        $subscriptions = $rep->getSubscriptionsForUser($user);

        foreach ($subscriptions as $subscription) {
            $this->assertSame(1, $subscription->getId());
        }
    }

    /**
     * Новая подписка должны быть добавлена в массив
     */
    public function testNewSubscriptionShouldBeSavedAsNewArrayItem()
    {
        $rep = new ArraySubscriptionsRepository([]);
        $newSubscription = $this->createNewSubscription();

        $rep->save($newSubscription);

        $saveSubscriptions = $rep->getData();
        $this->assertEquals(
            $this->getCupSubscriptionData(),
            end($saveSubscriptions)
        );
    }

    /**
     * Новая подписка должны быть сохранена с id = 1 если в хранилище совсем нет подписок
     */
    public function testNewSubscriptionShouldBeSavedWithIdEqualsTo1IfThereAreNoOtherSubscriptions()
    {
        $rep = new ArraySubscriptionsRepository([]);
        $newSubscription = $this->createNewSubscription();

        $rep->save($newSubscription);

        $saveSubscriptions = $rep->getData();
        $this->assertEquals(1, end($saveSubscriptions)['id']);
    }

    /**
     * Новая подписка должны быть сохранена с maxId + 1, где maxId - максимальный
     * айди из всех подписок хранилища
     */
    public function testNewSubscriptionShouldBeSavedWithIdEqualsToIncrementOfMaxIdFromStoredSubscriptions()
    {
        $maxId = 2;
        $rep = new ArraySubscriptionsRepository(
            [$this->getCupSubscriptionData(['id' => $maxId])]
        );
        $newSubscription = $this->createNewSubscription();

        $rep->save($newSubscription);

        $saveSubscriptions = $rep->getData();
        $this->assertEquals($maxId + 1, end($saveSubscriptions)['id']);
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
        $rows = [$this->getCupSubscriptionData($row)];
        $rep = new ArraySubscriptionsRepository($rows);
        $user = new User($userId, $rep);
        return array($rep, $user);
    }

    /**
     * Возвращает информацию о подписке на кружку
     * @param array $row поле => значение для подмены базовых данных
     * @return array
     */
    private function getCupSubscriptionData(array $row = []): array
    {
        return array_merge([
            'id' => 1,
            'name' => 'Кружка',
            'cost' => 100,
            'user_id' => '1',
            'end_date' => null,
            'start_date' => '2017-01-05 12:01:45',
            'delivery_type' => 'oncePerMonth',
            'delivery_day' => '1',
            'delivery_second_day_or_month' => null,
        ], $row);
    }

    /**
     * Возвращает модель свежесозданной подписки
     * @return Subscription
     */
    private function createNewSubscription(): Subscription
    {
        $data = $this->getCupSubscriptionData(['id' => null]);
        $newSubscription = SubscriptionFactory::createByRow($data);
        return $newSubscription;
    }
}

