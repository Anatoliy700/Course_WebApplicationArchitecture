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
        if (!isset($params['class']) || !isset($params['method'])) {
            throw new \InvalidArgumentException('Переданы не корректные параметры');
        }

        try {
            $object = (new \ReflectionClass($params['class']))->newInstance();
            $methodReflection = new \ReflectionMethod($object, $params['method']['name']);
        } catch (\ReflectionException $e) {
            throw new \InvalidArgumentException('Не найден класс или метод репозитория');
        }

        return $methodReflection->invokeArgs($object, $params['method']['params']);
    }
}