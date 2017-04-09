<?php
namespace ShavingShop\Models;

/**
 * Модель подписки пользователя
 */
class Subscription
{

    /**
     * Является ли подписка активной
     * @return bool
     */
    public function isActive()
    {
        return true;
    }
}