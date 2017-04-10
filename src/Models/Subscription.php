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
     * @var SubscriptionPeriod
     */
    private $period;

    /**
     * @var DeliveryInterface
     */
    private $delivery;

    public function __construct(Product $product, SubscriptionPeriod $period, DeliveryInterface $delivery)
    {
        $this->product = $product;
        $this->period = $period;
        $this->delivery = $delivery;
    }

    /**
     * Является ли подписка активной
     * @return bool
     */
    public function isActive()
    {
        $now = DateTime::now();
        $startDate = $this->period->getStartDate();
        $endDate = $this->period->getEndDate();
        return $startDate <= $now && ($endDate === null || $now < $endDate);
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
     * Возвращает информацию о доставке
     * @return DeliveryInterface
     */
    public function getDelivery(): DeliveryInterface
    {
        return $this->delivery;
    }

    /**
     * Возвращает период подписки
     * @return SubscriptionPeriod
     */
    public function getPeriod(): SubscriptionPeriod
    {
        return $this->period;
    }

    /**
     * Устанавливает время окончания подписки
     * @param DateTime $dateTime
     */
    public function setEndDate(DateTime $dateTime)
    {
        $this->period->setEndDate($dateTime);
    }
}