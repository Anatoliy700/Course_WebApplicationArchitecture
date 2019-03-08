<?php

namespace Service\DataMappers;

use Service\Adapters\Interfaces\IDbAdapter;
use Service\DataMappers\Interfaces\IMapper;
use Service\DbService\IdentityMap;
use Service\DbService\Interfaces\IDomainObject;
use Service\LazyLoad\ValueHolder;


abstract class Mapper implements IMapper
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

                    try {
                        $reflectClass = $parameter->getClass();
                        $nameClass = $reflectClass->getName();
                        $shortNameClass = substr($nameClass, strrpos($nameClass, '\\') + 1);
                        $namespace = (new \ReflectionClass($this))->getNamespaceName();
                        $mapperClass = $namespace . '\\' . $shortNameClass . 'Mapper';
                        $mapper = new $mapperClass($this->adapter);
                        $argObject = $reflectClass->newInstanceWithoutConstructor();
                        $argObjectProperty = $reflectClass->getProperty('id');
                        if (!$argObjectProperty->isPublic()) {
                            $argObjectProperty->setAccessible(true);
                        }
                        $argObjectProperty->setValue($argObject, $data[strtolower($shortNameClass) . '_id']);
                        $params[] = $argObject;
                        $lazyParams = [
                            $object,
                            $mapper,
                            (new \ReflectionClass($method->class))->getProperty(strtolower($shortNameClass))
                        ];
                    } catch (\ReflectionException $e) {
                        //TODO: Реализовать обработку исключения
                    }
                }
            }

            $method->invokeArgs($object, $params);
            if (isset($lazyParams)) {
                ValueHolder::setLazyLoad(...$lazyParams);
            }
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

        } catch (\ReflectionException $e) {

            //TODO: Реализовать обработку исключения
        }
    }
}