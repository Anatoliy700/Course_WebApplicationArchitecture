<?php

declare(strict_types = 1);

namespace Service\Order;

use Model;
use Model\Repository\Interfaces\IBasket;
use Service\Adapters\RepositoryAdapter;
use Service\Commands\Interfaces\IBasketCheckout;
use Service\Product\Product;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Basket implements IBasketCheckout
{
    /**
     * Сессионный ключ списка всех продуктов корзины
     */
    private const BASKET_DATA_KEY = 'basket';

    /**
     * @var SessionInterface
     */
    private $repository;

    /**
     *
     * @var array
     */
    private $productIds;

    /**
     * @param IBasket $repository
     */
    public function __construct(IBasket $repository)
    {
        $this->repository = $repository;
        $this->getBasket();
    }

    /**
     * Получаем корзину из репозитория
     */
    private function getBasket()
    {
        $this->productIds = $this->repository->get(static::BASKET_DATA_KEY, []);
    }

    /**
     * Сохраняет корзину в репозитории
     */
    private function saveBasket()
    {
        $this->repository->save($this->productIds);
    }

    /**
     * Добавляем товар в заказ
     *
     * @param int $product
     *
     * @return void
     */
    public function addProduct(int $product): void
    {
        if (!$this->isProductInBasket($product)) {
            $this->productIds[] = $product;
            $this->saveBasket();
        }
    }

    /**
     * Проверяем, лежит ли продукт в корзине или нет
     *
     * @param int $productId
     *
     * @return bool
     */
    public function isProductInBasket(int $productId): bool
    {
        return in_array($productId, $this->productIds, true);
    }

    /**
     * Получаем информацию по всем продуктам в корзине
     *
     * @return Model\Entity\Product[]
     */
    public function getProductsInfo(): array
    {
        return $this->getProductRepository()->search($this->productIds);
    }

    /**
     * Фабричный метод для репозитория Product
     *
     */
    protected function getProductRepository()
    {
        return Product::getProductRepository();
    }

    /**
     * Расчитываем общую стоимость товаров в корзине
     *
     * @return float
     */
    public function getTotalPrice(): float
    {
        $totalPrice = 0;
        foreach ($this->getProductsInfo() as $product) {
            $totalPrice += $product->getPrice();
        }
        return $totalPrice;
    }
}
