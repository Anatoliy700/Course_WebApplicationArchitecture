<?php

namespace Service\DataMappers;

use Service\Adapters\Interfaces\IDbAdapter;
use Service\DbService\IdentityMap;
use Service\DbService\Interfaces\IDomainObject;


abstract class Mapper
{
    /**
     * @var IDbAdapter
     */
    protected $adapter;

    /**
     * @var IdentityMap
     */
    private $identityMap;

    /**
     * UserMapper constructor.
     * @param IDbAdapter $adapter
     */
    final public function __construct(IDbAdapter $adapter)
    {
        $this->adapter = $adapter;
        $this->identityMap = IdentityMap::getInstance();
    }

    /**
     * @return string
     */
    abstract protected function getEntityClass(): string;

    /**
     * @param int $id
     * @return IDomainObject
     * @throws \Service\DbService\Exceptions\EmptyCacheException
     */
    final protected function getFromCache(int $id): IDomainObject
    {
        return $this->identityMap->get($this->getEntityClass(), $id);
    }

    /**
     * @param IDomainObject $object
     */
    final protected function setInCache(IDomainObject $object)
    {
        $this->identityMap->add($object);
    }

    /**
     * Создает экземпляр класса
     *
     * @param array $data
     * @return IDomainObject
     */
    protected function objectFactory(array $data): IDomainObject
    {
        /**
         * Если переданный метод сеттер, то возвращает имя свойства, которому он устанавливает занчение,
         * иначе возвращает NULL
         *
         * @param string $setterName
         * @return string|null
         */
        $getPropertyNameFromSetterMethod = function (string $setterName): ?string {
            if (preg_match('#^set([A-Z][a-z]*)$#', $setterName, $matches)) {

                return strtolower($matches[1]);
            }

            return null;
        };

        /**
         * Проверяет является ли переданный метод сеттером
         *
         * @param string $setterName
         * @return bool
         */
        $isSetterMethod = function (string $setterName) use ($getPropertyNameFromSetterMethod): bool {
            return (bool)$getPropertyNameFromSetterMethod($setterName);
        };

        /**
         * Устанавливает значения свойств объекта через сеттеры или конструктор
         *
         * @param IDomainObject $object
         * @param \ReflectionMethod $method
         * @param array $data
         */
        $setPropertiesObject = function (
            IDomainObject $object,
            \ReflectionMethod $method,
            array $data
        ) use ($getPropertyNameFromSetterMethod) {

            $params = [];

            /** @var \ReflectionParameter $parameter */
            foreach ($method->getParameters() as $parameter) {

                if (!$parameter->getClass()) {

                    $params[] = $data[$getPropertyNameFromSetterMethod($method->getName()) ?: $parameter->getName()];

                } else {

                    $nameClass = $parameter->getClass()->getName();
                    $shortNameClass = substr($nameClass, strrpos($nameClass, '\\') + 1);
                    $namespace = (new \ReflectionClass($this))->getNamespaceName();
                    $mapperClass = $namespace . '\\' . $shortNameClass . 'Mapper';
                    $mapper = new $mapperClass($this->adapter);
                    $params[] = $mapper->getById($data[strtolower($shortNameClass) . '_id']);
                }
            }

            $method->invokeArgs($object, $params);
        };

        try {
            $reflectionClass = new \ReflectionClass($this->getEntityClass());

            /** @var IDomainObject $object */
            $object = $reflectionClass->newInstanceWithoutConstructor();

            $constructor = $reflectionClass->getConstructor();
            if ($constructor && count($constructor->getParameters())) {
                $setPropertiesObject($object, $constructor, $data);
            }

            $methods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC);
            foreach ($methods as $method) {

                if ($isSetterMethod($method->getName())) {
                    $setPropertiesObject($object, $method, $data);
                }
            }

            return $object;

        } catch (\ReflectionException $e) { //TODO: Реализовать обработку исключения
        }
    }
}