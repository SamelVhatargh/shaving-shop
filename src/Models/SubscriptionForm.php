<?php
namespace ShavingShop\Models;

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
}
