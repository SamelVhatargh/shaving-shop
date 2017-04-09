<?php
namespace ShavingShop\Models;

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

    public function __construct(Product $product, DateTime $startDate)
    {
        $this->product = $product;
        $this->startDate = $startDate;
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
}