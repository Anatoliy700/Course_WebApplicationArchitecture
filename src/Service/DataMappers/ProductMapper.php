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
            $productsData = $this->adapter->find([
                'class' => ProductRepository::class,
                'method' => [
                    'name' => 'find',
                    'params' => [
                        $noCachedIds,
                    ]
                ]
            ]);


            if (!$productsData) {
                throw new \Exception('Продукты не найдены');
            }

            $noCachedProducts = $this->createProducts($productsData);

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
            'method' => [
                'name' => 'getAllProductIds',
                'params' => []
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
            $products[] = $this->objectFactory($item);
        }

        return $products;
    }
}