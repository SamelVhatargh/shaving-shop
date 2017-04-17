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

    /**
     * Возвращает модель товара по имени
     * @param string $name имя товара
     * @return Product
     */
    public function findByName(string $name): ?Product;
}