<?php
namespace ShavingShop\Tests\Models;

use PHPUnit_Framework_MockObject_MockObject;
use ShavingShop\Models\SubscriptionForm;
use PHPUnit\Framework\TestCase;
use ShavingShop\Models\User;
use ShavingShop\Repositories\SubscriptionRepositoryInterface;
use ShavingShop\Utils\DateTime;
use Slim\Http\Request;

/**
 * Тесты формы подписки
 */
class SubscriptionFormTest extends TestCase
{

    /**
     * Поля формы должны заполнятся из POST-данных запроса
     */
    public function testFormFieldsShouldPopulateFromPostData()
    {
        $postData = ['day' => 3];
        $request = $this->getRequestMock();
        $request->expects($this->once())
            ->method('getParsedBodyParam')
            ->with('Subscription')
            ->willReturn($postData);
        $request->method('isPost')->willReturn(true);
        $form = $this->createForm();

        $form->populateFromPostRequest($request);

        $this->assertEquals($postData['day'], $form->deliveryDay);
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
     * Модель подписки созданная формой должна содержать значения с формы
     */
    public function testCreateSubscriptionShouldReturnSubscriptionModelBasedOnFormFields()
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
     * @return SubscriptionForm
     */
    private function createForm(): SubscriptionForm
    {
        $rep = $this->getMockBuilder(SubscriptionRepositoryInterface::class)
            ->getMock();
        $user = new User(1, $rep);
        return new SubscriptionForm($user);
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
