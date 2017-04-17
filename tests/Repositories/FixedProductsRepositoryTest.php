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
     * @dataProvider productsDataProvider
     */
    public function testFindAllReturnSpecificProduct(Product $specificProduct)
    {
        $repo = new FixedProductsRepository();

        $products = $repo->findAll();

        $this->assertContains($specificProduct, $products, '', false, false);
    }

    /**
     * При поиске по имени должна возвращаться модель с тем же именем
     * @param Product $product
     * @dataProvider productsDataProvider
     */
    public function testFindByNameShouldReturnProductWithTheSameNameAsRequested(Product $product) {
        $repo = new FixedProductsRepository();

        $foundProduct = $repo->findByName($product->name);

        $this->assertEquals($product, $foundProduct);
    }


    public function testFindByNameShouldReturnNullIfProductWithRequestedNameDoesNotExist()
    {
        $repo = new FixedProductsRepository();
        $nonexistentName = 'Футбольный мяч';

        $product = $repo->findByName($nonexistentName);

        $this->assertNull($product);
    }

    public function productsDataProvider()
    {
        return [
            [new Product('Бритвенный станок', 1)],
            [new Product('Бритвенный станок + гель для бритья', 9)],
            [new Product('Бритвенный станок + гель + средство после бритья', 19)],
        ];
    }
}

