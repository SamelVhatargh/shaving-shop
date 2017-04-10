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

    /**
     * @var DateTime
     */
    private $endDate;

    public function __construct(Product $product, DateTime $startDate, DeliveryInterface $delivery, DateTime $endDate = null)
    {
        $this->product = $product;
        $this->startDate = $startDate;
        $this->delivery = $delivery;
        $this->endDate = $endDate;
    }

    /**
     * Является ли подписка активной
     * @return bool
     */
    public function isActive()
    {
        $now = DateTime::now();
        return $this->startDate <= $now && ($this->endDate === null || $now < $this->endDate);
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