<?php
namespace ShavingShop\Models;

/**
 * Модель подписки пользователя
 */
class Subscription
{

    /**
     * @var Product
     */
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
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
}