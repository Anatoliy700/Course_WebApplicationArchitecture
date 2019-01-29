<?php

declare(strict_types = 1);

namespace Service\Product;

use Model;
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
        $product = $this->getProductRepository()->search([$id]);
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
        $productList = $this->getProductRepository()->fetchAll();

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
     * Фабричный метод для репозитория Product
     *
     * @return Model\Repository\Product
     */
    protected function getProductRepository(): Model\Repository\Product
    {
        return new Model\Repository\Product();
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
