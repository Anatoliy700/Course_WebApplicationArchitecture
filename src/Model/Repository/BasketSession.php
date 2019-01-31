<?php

namespace Model\Repository;


use Model\Repository\Interfaces\IBasket;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BasketSession implements IBasket
{
    /**
     * Сессионный ключ списка всех продуктов корзины
     */
    private const BASKET_DATA_KEY = 'basket';

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Получаем корзину из репозитория
     * @return array
     */
    public function get(): array
    {
        return $this->session->get(static::BASKET_DATA_KEY, []);
    }

    /**
     * Сохраняем корзину в репозитории
     * @param $basket
     * @return void
     */
    public function save(array $basket): void
    {
        $this->session->set(static::BASKET_DATA_KEY, $basket);
    }

}