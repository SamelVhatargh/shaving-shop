<?php
namespace ShavingShop\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use ShavingShop\Models\User;
use ShavingShop\Repositories\ArraySubscriptionsRepository;
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

    public function __construct(ContainerInterface $container)
    {
        $this->view = $container->get('view');
        $this->subsRepo = new ArraySubscriptionsRepository(
            $_SESSION['data']['subscriptions'] ?? []
        );
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
            $_SESSION['data']['subscriptions'] = $this->subsRepo->getData();
        }
        return $response->withRedirect('/', 301);
    }

    public function restore(RequestInterface $request, ResponseInterface $response, $args)
    {
        restore();
        return $response->withRedirect('/', 301);
    }
}