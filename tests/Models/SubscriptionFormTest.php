<?php
namespace ShavingShop\Tests\Models;

use PHPUnit_Framework_MockObject_MockObject;
use ShavingShop\Models\Deliveries\OncePerMonthDelivery;
use ShavingShop\Models\Subscription;
use ShavingShop\Models\SubscriptionFactory;
use ShavingShop\Models\SubscriptionForm;
use PHPUnit\Framework\TestCase;
use ShavingShop\Models\User;
use ShavingShop\Repositories\FixedProductsRepository;
use ShavingShop\Repositories\SubscriptionRepositoryInterface;
use ShavingShop\Utils\DateTime;
use Slim\Http\Request;

/**
 * Тесты формы подписки
 */
class SubscriptionFormTest extends TestCase
{


    /**
     * @param Subscription $subscription
     * @param $expected
     * @param $formField
     * @dataProvider subscriptionPopulateDataProvider
     */
    public function testFormFieldsShouldPopulateFromSubscription(Subscription $subscription, $expected, $formField)
    {
        $form = $this->createForm();

        $form->populateFromSubscription($subscription);

        $this->assertEquals($expected, $form->$formField);
    }

    public function subscriptionPopulateDataProvider()
    {
        $subscription = SubscriptionFactory::createByRow(
            [
                'id' => 1,
                'name' => 'Кружка',
                'cost' => 100,
                'user_id' => '1',
                'end_date' => null,
                'start_date' => '2017-01-05 12:01:45',
                'delivery_type' => 'oncePerMonth',
                'delivery_day' => '1',
                'delivery_second_day_or_month' => null,
            ]
        );
        return [
            [$subscription, $subscription->getDelivery()->getDeliveryDay(), 'deliveryDay'],
            [$subscription, $subscription->getDelivery()->getId(), 'deliveryType'],
            [$subscription, $subscription->getDelivery()->getSecondDeliveryDayOrMonth(), 'deliverySecondDayOrMonth'],
            [$subscription, $subscription->getProduct()->name, 'product'],
        ];
    }

    /**
     * Поля формы должны заполнятся из POST-данных запроса
     * @dataProvider postDataProvider
     */
    public function testFormFieldsShouldPopulateFromPostData($postField, $formField)
    {
        $postData = [$postField => 3];
        $request = $this->getRequestMock();
        $request->expects($this->once())
            ->method('getParsedBodyParam')
            ->with('Subscription')
            ->willReturn($postData);
        $request->method('isPost')->willReturn(true);
        $form = $this->createForm();

        $form->populateFromPostRequest($request);

        $this->assertEquals($postData[$postField], $form->$formField);
    }

    public function postDataProvider() {
        return [
            ['day', 'deliveryDay'],
            ['type', 'deliveryType'],
            ['secondDay', 'deliverySecondDayOrMonth'],
            ['product', 'product'],
        ];
    }

    /**
     * Форма считается отправленной если странице запрошена POST-методом
     */
    public function testFormIsConsideredSubmittedIfPageIsRequestedWithPostMethod()
    {
        $request = $this->getRequestMock();
        $request->method('isPost')->willReturn(true);
        $form = $this->createForm();

        $form->populateFromPostRequest($request);

        $this->assertTrue($form->isSubmitted());
    }

    /**
     * Форма не считается отправленной если странице запрошена не POST-методом
     */
    public function testFormIsNotConsideredSubmittedIfPageIsNotRequestedWithPostMethod()
    {
        $request = $this->getRequestMock();
        $request->method('isPost')->willReturn(false);
        $form = $this->createForm();

        $form->populateFromPostRequest($request);

        $this->assertFalse($form->isSubmitted());
    }

    /**
     * Модель подписки созданная формой должна содержать указанный в форме день доставки
     */
    public function testCreatedSubscriptionShouldHaveDeliveryDayFromForm()
    {
        $form = $this->createForm();
        $form->deliveryDay = '2';

        $subscription = $form->createSubscription();

        $this->assertEquals(
            $form->deliveryDay,
            $subscription->getDelivery()->getDeliveryDay()
        );
    }

    /**
     * Модель подписки созданная формой должна содержать указанный в форме тип доставки
     */
    public function testCreatedSubscriptionShouldHaveDeliveryTypeFromForm()
    {
        $form = $this->createForm();
        $form->deliveryType = 'oncePerMonth';

        $subscription = $form->createSubscription();

        $this->assertInstanceOf(
            OncePerMonthDelivery::class,
            $subscription->getDelivery()
        );
    }

    /**
     * Модель подписки созданная формой должна содержать указанный в форме товар
     */
    public function testCreatedSubscriptionShouldHaveProductFromForm()
    {
        $form = $this->createForm();
        $form->product = 'Бритвенный станок + гель для бритья';
        $product = (new FixedProductsRepository())->findByName($form->product);

        $subscription = $form->createSubscription();

        $this->assertEquals($product, $subscription->getProduct());
    }

    /**
     * Модель подписки созданная формой должна иметь сегодняшюю дату в качестве
     * даты начала подписки
     */
    public function testCreatedSubscriptionShouldHaveTodayAsStartDate()
    {
        $form = $this->createForm();
        $now = DateTime::now();

        $subscription = $form->createSubscription();

        $this->assertEquals(
            $now->format('Y-m-d H:i:s'),
            $subscription->getPeriod()->getStartDate()->format('Y-m-d H:i:s')
        );
    }

    /**
     * Модель подписки созданная формой не должна иметь дату окончания
     */
    public function testCreatedSubscriptionShouldNotHaveEndDate()
    {
        $form = $this->createForm();

        $subscription = $form->createSubscription();

        $this->assertNull(
            $subscription->getPeriod()->getEndDate()
        );
    }

    /**
     * @return SubscriptionForm
     */
    private function createForm(): SubscriptionForm
    {
        $rep = $this->getMockBuilder(SubscriptionRepositoryInterface::class)
            ->getMock();
        $user = new User(1, $rep);
        return new SubscriptionForm($user, new FixedProductsRepository());
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    private function getRequestMock(): PHPUnit_Framework_MockObject_MockObject
    {
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
        return $request;
    }
}
