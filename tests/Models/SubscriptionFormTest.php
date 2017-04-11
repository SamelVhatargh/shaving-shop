<?php
namespace ShavingShop\Tests\Models;

use ShavingShop\Models\SubscriptionForm;
use PHPUnit\Framework\TestCase;
use ShavingShop\Models\User;
use ShavingShop\Repositories\SubscriptionRepositoryInterface;
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
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();
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
     * @return SubscriptionForm
     */
    private function createForm(): SubscriptionForm
    {
        $rep = $this->getMockBuilder(SubscriptionRepositoryInterface::class)
            ->getMock();
        $user = new User(1, $rep);
        return new SubscriptionForm($user);
    }
}
