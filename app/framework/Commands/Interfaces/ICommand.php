<?php

namespace Framework\Commands\Interfaces;

/**
 * Описывает интерфейс запуска команды
 *
 * Interface ICommand
 * @package Framework\Commands\Interfaces
 */
interface ICommand
{
    public function execute(array $params): array;
}