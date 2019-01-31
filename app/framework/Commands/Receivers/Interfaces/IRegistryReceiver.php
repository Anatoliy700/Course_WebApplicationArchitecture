<?php

namespace Framework\Commands\Receivers\Interfaces;

/**
 * Описывает интерфейс запуска регистрации
 *
 * Interface IRegistryReceiver
 * @package Framework\Commands\Receivers\Interfaces
 */
interface IRegistryReceiver
{
    public function registry(): void;
}