<?php
namespace ShavingShop\Repositories;

use ShavingShop\Models\Product;

/**
 * Репозиторий продуктов, возвращающий конкретные модели товаров
 */
class FixedProductsRepository implements ProductsRepositoryInterface
{

    /**
     * Возвращает модели всех товаров
     * @return Product[]
     */
    public function findAll(): array
    {
        return array_map(function (array $productData) {
            return new Product($productData['name'], $productData['cost']);
        }, [
            [
                'name' => 'Бритвенный станок',
                'cost' => '1',
            ],
            [
                'name' => 'Бритвенный станок + гель для бритья',
                'cost' => '9',
            ],
            [
                'name' => 'Бритвенный станок + гель + средство после бритья',
                'cost' => '19',
            ],
        ]);
    }
}
