<?php
namespace ShavingShop\Models;

use ShavingShop\Repositories\ProductsRepositoryInterface;
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
     * @var string
     */
    public $name = '';

    /**
     * @var ProductsRepositoryInterface
     */
    private $productsRepository;

    /**
     * SubscriptionForm constructor.
     * @param User $user
     * @param ProductsRepositoryInterface $productsRepository
     */
    public function __construct(User $user, ProductsRepositoryInterface $productsRepository)
    {
        $this->user = $user;
        $this->productsRepository = $productsRepository;
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
            $this->name = $data['product'] ?? '';

            $this->submitted = true;
        }
    }

    /**
     * Заполняет форму данными с существующей подписки
     * @param Subscription $subscription
     */
    public function populateFromSubscription(Subscription $subscription) {
        $this->deliveryDay = $subscription->getDelivery()->getDeliveryDay();
        $this->name = $subscription->getProduct()->name;
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
        $product = $this->productsRepository->findByName($this->name);
        $subscription = SubscriptionFactory::createByRow([
            'id' => null,
            'name' => $product->name ?? '',
            'cost' => $product->cost ?? 0,
            'start_date' => DateTime::now()->format('Y-m-d H:i:s'),
            'end_date' => null,
            'delivery_day' => $this->deliveryDay,
            'user_id' => $this->user->getId(),
        ]);
        return $subscription;
    }
}
