<?php
namespace ShavingShop\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use ShavingShop\Models\User;
use ShavingShop\Repositories\ArraySubscriptionsRepository;
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

    public function __construct(ContainerInterface $container)
    {
        $this->view = $container->get('view');
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
        $subsRepo = new ArraySubscriptionsRepository($_SESSION['data']['subscriptions'] ?? []);
        $user = new User(1, $subsRepo);

        return $this->view->render($response, 'home.phtml', [
            'activeSubscription' => $user->getActiveSubscription()
        ]);
    }
}