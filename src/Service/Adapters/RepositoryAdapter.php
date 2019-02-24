<?php

namespace Service\Adapters;


use Service\Adapters\Interfaces\IDbAdapter;

class RepositoryAdapter implements IDbAdapter
{
    /**
     * @param array $params
     * @return array|null
     * @throws \ReflectionException
     */
    public function find(array $params): ?array
    {
        if (!isset($params['class'])) {
            throw new \InvalidArgumentException('Не найден класс репозитория');
        }

        $reflection = new \ReflectionClass($params['class']);
        if (!isset($params['params']) || !count($params['params'])) {
            throw new \InvalidArgumentException('Переданы не корректные параметры');
        }

        $param = $params['params'][0];
        $methodName = $param['method'];
        if (!$reflection->hasMethod($methodName)) {
            throw new \InvalidArgumentException('Не найден метод репозитория');
        }

        $class = $reflection->newInstance();

        return $class->$methodName($param['value']);
    }
}