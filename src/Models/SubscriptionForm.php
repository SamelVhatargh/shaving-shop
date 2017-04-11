<?php
namespace ShavingShop\Models;

use ShavingShop\Utils\DateTime;
use Slim\Http\Request;

/**
 * Форма добавления или изменения подписки
 */
class SubscriptionForm
{

    /**
     * @var int День доставки
     */
    public $deliveryDay = 1;

    /**
     * @var User
     */
    private $user;

    /**
     * @var bool
     */
    private $submitted = false;

    /**
     * SubscriptionForm constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * Заполняет форму POST из запроса
     * @param Request $request
     */
    public function populateFromPostRequest(Request $request)
    {
        if ($request->isPost()) {
            $data = $request->getParsedBodyParam('Subscription', false);
            $this->deliveryDay = $data['day'] ?? '';

            $this->submitted = true;
        }
    }

    /**
     * Была ли отправлена форма
     * @return bool
     */
    public function isSubmitted(): bool
    {
        return $this->submitted;
    }

    /**
     * Возвращает модель подписки
     * @return Subscription
     */
    public function createSubscription(): Subscription
    {
        $subscription = SubscriptionFactory::createByRow([
            'id' => null,
            'name' => 'Бритвенный станок станок',
            'cost' => '1',
            'start_date' => DateTime::now()->format('Y-m-d H:i:s'),
            'end_date' => null,
            'delivery_day' => $this->deliveryDay,
            'user_id' => $this->user->getId(),
        ]);
        return $subscription;
    }
}
