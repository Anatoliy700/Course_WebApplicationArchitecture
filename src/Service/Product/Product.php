<?php

declare(strict_types = 1);

namespace Service\Product;

use Model;
use Service\Adapters\RepositoryAdapter;
use Service\DataMappers\ProductMapper;
use Service\Product\SortingOptions\Interfaces\IComparator;
use Service\Product\SortingOptions\NameComparator;
use Service\Product\SortingOptions\PriceComparator;

class Product
{
    /**
     * Получаем информацию по конкретному продукту
     *
     * @param int $id
     * @return Model\Entity\Product|null
     */
    public function getInfo(int $id): ?Model\Entity\Product
    {
        $product = static::getProductRepository()->search([$id]);
        return count($product) ? $product[0] : null;
    }

    /**
     * Получаем все продукты
     *
     * @param string $sortType
     *
     * @return Model\Entity\Product[]
     */
    public function getAll(string $sortType): array
    {
        $productList = static::getProductRepository()->fetchAll();

        // Применить паттерн Стратегия
        // $sortType === 'price'; // Сортировка по цене
        // $sortType === 'name'; // Сортировка по имени

        if ($sortType === 'price') {
            $productList = $this->sort(new PriceComparator(), $productList);
        } elseif ($sortType === 'name'){
            $productList = $this->sort(new NameComparator(), $productList);
        }
        return $productList;
    }

    /**
     * Статический фабричный метод для репозитория Product
     *
     * @return ProductMapper
     */
    public static function getProductRepository(): ProductMapper
    {
        return new ProductMapper(new RepositoryAdapter());
    }

    /**
     * Запускает сортировку товаров
     *
     * @param IComparator $comparator
     * @param array $products
     * @return array
     */
    protected function sort(IComparator $comparator, array $products)
    {
        return $comparator->compare($products);
    }
}
