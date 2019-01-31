<?php

namespace Model\Repository\Interfaces;


interface IBasket
{
    /**
     * Получаем корзину из репозитория
     * @return array
     */
    public function get(): array;

    /**
     * Сохраняем корзину в репозитории
     * @param $basket
     * @return void
     */
    public function save(array $basket): void ;
}