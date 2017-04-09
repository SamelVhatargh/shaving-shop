<?php
namespace ShavingShop\Models;

use ShavingShop\Models\Deliveries\DeliveryInterface;
use ShavingShop\Utils\DateTime;

/**
 * Модель подписки пользователя
 */
class Subscription
{

    /**
     * @var Product
     */
    private $product;

    /**
     * @var DateTime
     */
    private $startDate;

    /**
     * @var DeliveryInterface
     */
    private $delivery;

    public function __construct(Product $product, DateTime $startDate, DeliveryInterface $delivery)
    {
        $this->product = $product;
        $this->startDate = $startDate;
        $this->delivery = $delivery;
    }

    /**
     * Является ли подписка активной
     * @return bool
     */
    public function isActive()
    {
        return true;
    }

    /**
     * Возвращает товар, на который осуществлена подписка
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * Возвращает дату начала подписки
     * @return DateTime
     */
    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    /**
     * Возвращает информацию о доставке
     * @return DeliveryInterface
     */
    public function getDelivery(): DeliveryInterface
    {
        return $this->delivery;
    }
}