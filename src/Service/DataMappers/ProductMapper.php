<?php

namespace Service\DataMappers;


use Model\Entity\Product;
use Model\Repository\ProductRepository;
use Service\DbService\Exceptions\EmptyCacheException;

class ProductMapper extends Mapper
{
    protected function getEntityClass(): string
    {
        return Product::class;
    }

    /**
     * @param array $ids
     * @return array
     * @throws \Exception
     */
    public function search(array $ids): array
    {
        $products = [];
        $noCachedIds = [];
        $noCachedProducts = [];

        if (count($ids)) {
            foreach ($ids as $id) {
                try {
                    $products[] = $this->getFromCache($id);
                } catch (EmptyCacheException $e) {
                    $noCachedIds[] = $id;
                }
            }
        }

        if (count($noCachedIds)) {
            $items = $this->adapter->find([
                'class' => ProductRepository::class,
                'params' => [
                    [
                        'method' => 'find',
                        'value' => $noCachedIds
                    ]
                ]
            ]);


            if (!$items) {
                throw new \Exception('Продукты не найдены');
            }

            $noCachedProducts = $this->createProducts($items);

            foreach ($noCachedProducts as $product) {
                $this->setInCache($product);
            }
        }

        return array_merge($products, $noCachedProducts);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function fetchAll(): array
    {
        $productIds = $this->adapter->find([
            'class' => ProductRepository::class,
            'params' => [
                [
                    'method' => 'getAllProductIds',
                    'value' => []
                ]
            ]
        ]);
        if (count($productIds)) {
            return $this->search($productIds);
        }
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