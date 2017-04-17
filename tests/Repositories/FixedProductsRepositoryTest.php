<?php
namespace ShavingShop\Tests\Repositories;

use ShavingShop\Models\Product;
use ShavingShop\Repositories\FixedProductsRepository;
use PHPUnit\Framework\TestCase;

/**
 * Тесты репозитория товаров
 */
class FixedProductsRepositoryTest extends TestCase
{

    /**
     * Репозиторий возвращает модели товаров
     */
    public function testFindAllReturnsOnlyProducts()
    {
        $repo = new FixedProductsRepository();

        $products = $repo->findAll();

        $this->assertContainsOnlyInstancesOf(Product::class, $products);
    }

    /**
     * Репозиторий возвращает модель определенного товара
     * @param Product $specificProduct
     * @dataProvider findAllReturnSpecificProductDataProvider
     */
    public function testFindAllReturnSpecificProduct(Product $specificProduct)
    {
        $repo = new FixedProductsRepository();

        $products = $repo->findAll();

        $this->assertContains($specificProduct, $products, '', false, false);
    }

    public function findAllReturnSpecificProductDataProvider()
    {
        return [
            [new Product('Бритвенный станок', 1)],
            [new Product('Бритвенный станок + гель для бритья', 9)],
            [new Product('Бритвенный станок + гель + средство после бритья', 19)],
        ];
    }
}

