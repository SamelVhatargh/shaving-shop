<?php
namespace ShavingShop\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use ShavingShop\Models\Deliveries\DeliveryInterface;
use ShavingShop\Models\Deliveries\OncePerMonthDelivery;
use ShavingShop\Models\SubscriptionForm;
use ShavingShop\Models\User;
use ShavingShop\Repositories\ArraySubscriptionsRepository;
use ShavingShop\Repositories\FixedProductsRepository;
use ShavingShop\Utils\DateTime;
use Slim\Views\PhpRenderer;

/**
 *
 */
class SubscriptionsController
{

    /**
     * @var PhpRenderer
     */
    protected $view;

    /**
     * @var ArraySubscriptionsRepository
     */
    private $subsRepo;

    /**
     * @var User
     */
    private $user;

    /**
     * @var FixedProductsRepository
     */
    private $productsRepo;

    public function __construct(ContainerInterface $container)
    {
        $this->view = $container->get('view');
        $this->subsRepo = new ArraySubscriptionsRepository(
            $_SESSION['data']['subscriptions'] ?? []
        );
        $this->productsRepo = new FixedProductsRepository();
        $this->user = new User(1, $this->subsRepo);
    }

    /**
     * Стартовая страница
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function active(RequestInterface $request, ResponseInterface $response)
    {
        return $this->view->render($response, 'home.phtml', [
            'activeSubscription' => $this->user->getActiveSubscription()
        ]);
    }

    /**
     * История доставок
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function history(RequestInterface $request, ResponseInterface $response)
    {
        return $this->view->render($response, 'deliveries.phtml', [
            'subscriptions' => $this->user->getSubscriptions()
        ]);
    }

    /**
     * Отмена подписки
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
    public function clear(RequestInterface $request, ResponseInterface $response, $args)
    {
        $subscriptionId = $args['id'];
        $subscription = $this->subsRepo->getById($subscriptionId);

        if ($subscription !== null) {
            $this->user->cancelSubscription($subscription);
            $this->updateSessionStorage();
        }
        return $response->withRedirect('/', 301);
    }

    /**
     * Отображает форму создания подписки и сохраняет ее если нужно
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function create(RequestInterface $request, ResponseInterface $response)
    {
        $form = new SubscriptionForm($this->user, $this->productsRepo);

        $form->populateFromPostRequest($request);
        if ($form->isSubmitted()) {
            $this->subsRepo->save($form->createSubscription());
            $this->updateSessionStorage();
            return $response->withRedirect('/', 301);
        }

        return $this->view->render($response, 'create.phtml', [
            'form' => $form,
            'products' => $this->productsRepo->findAll(),
            'deliveries' => $this->getAvailableDeliveries(),
            'action' => 'Добавление',
        ]);
    }

    /**
     * Отображает форму смены подписки и сохраняет ее если нужно
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function update(RequestInterface $request, ResponseInterface $response)
    {
        $activeSubscription = $this->user->getActiveSubscription();
        $form = new SubscriptionForm($this->user, $this->productsRepo);


        $form->populateFromPostRequest($request);
        if ($form->isSubmitted()) {
            $this->user->cancelSubscription($activeSubscription);
            $this->subsRepo->save($form->createSubscription());
            $this->updateSessionStorage();
            return $response->withRedirect('/', 301);
        }
        $form->populateFromSubscription($activeSubscription);

        return $this->view->render($response, 'create.phtml', [
            'form' => $form,
            'products' => $this->productsRepo->findAll(),
            'deliveries' => $this->getAvailableDeliveries(),
            'action' => 'Смена',
        ]);
    }

    public function restore(RequestInterface $request, ResponseInterface $response, $args)
    {
        restore();
        return $response->withRedirect('/', 301);
    }

    /**
     * Обновляет сессию свежими данными о подписках
     */
    private function updateSessionStorage(): void
    {
        $_SESSION['data']['subscriptions'] = $this->subsRepo->getData();
    }

    /**
     * Возвращает доступные виды доставки
     * @return DeliveryInterface[]
     */
    private function getAvailableDeliveries(): array
    {
        return [
            new OncePerMonthDelivery(1),
        ];
    }
}