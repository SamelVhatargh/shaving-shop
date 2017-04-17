<?php
namespace ShavingShop\Repositories;


use ShavingShop\Models\Product;

interface ProductsRepositoryInterface
{

    /**
     * Возвращает модели всех товаров
     * @return Product[]
     */
    public function findAll(): array;
}