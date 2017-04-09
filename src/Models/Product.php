<?php
namespace ShavingShop\Models;

/**
 * Модель товара
 */
class Product
{
    /**
     * Название товара
     * @var string
     */
    public $name;

    /**
     * Стоимость товара
     * @var int
     */
    public $cost;

    /**
     * Product constructor.
     * @param string $name
     * @param int $cost
     */
    public function __construct(string $name, int $cost)
    {
        $this->name = $name;
        $this->cost = $cost;
    }
}
