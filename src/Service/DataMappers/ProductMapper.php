<?php

namespace Service\DataMappers;


use Model\Entity\Product;
use Model\Repository\ProductRepository;

class ProductMapper extends Mapper
{
    /**
     * @param array $ids
     * @return array
     * @throws \Exception
     */
    public function search(array $ids): array
    {
        $items = $this->adapter->find([
            'class' => ProductRepository::class,
            'params' => [
                [
                    'method' => 'find',
                    'value' => $ids
                ]
            ]
        ]);


        if (!$items) {
            throw new \Exception('Продукты не найдены');
        }

        return $this->createProducts($items);

    }

    /**
     * @return array
     * @throws \Exception
     */
    public function fetchAll(): array
    {
        $items = $this->adapter->find([
            'class' => ProductRepository::class,
            'params' => [
                [
                    'method' => 'find',
                    'value' => []
                ]
            ]
        ]);


        if (!$items) {
            throw new \Exception('Продукты не найдены');
        }

        return $this->createProducts($items);
    }

    /**
     * @param array $items
     * @return array
     */
    protected function createProducts(array $items): array
    {
        $products = [];

        foreach ($items as $item) {
            $products[] = $this->productFactory($item);
        }

        return $products;
    }

    /**
     * @param array $item
     * @return Product
     */
    protected function productFactory(array $item): Product
    {
        $product = new Product();
        $product
            ->setId($item['id'])
            ->setName($item['name'])
            ->setPrice($item['price']);

        return $product;
    }
}