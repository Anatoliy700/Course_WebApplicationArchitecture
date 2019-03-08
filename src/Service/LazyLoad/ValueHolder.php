<?php

namespace Service\LazyLoad;


use Service\DataMappers\Interfaces\IMapper;
use Service\DbService\Interfaces\IDomainObject;

class ValueHolder
{
    /**
     * @var IDomainObject
     */
    private $_object;

    /**
     * @var int
     */
    private $object_id;

    /**
     * @var IMapper
     */
    private $mapper;

    /**
     * ValueHolder constructor.
     * @param IMapper $mapper
     * @param int $object_id
     */
    public function __construct(IMapper $mapper, int $object_id)
    {
        $this->mapper = $mapper;
        $this->object_id = $object_id;
    }

    /**
     * @return IDomainObject|null
     */
    private function getObject()
    {
        if (!$this->_object) {
            $this->_object = $this->mapper->getById($this->object_id);
        }

        return $this->_object;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->getObject()->$name($arguments);
    }

    /**
     * @param IDomainObject $object
     * @param IMapper $mapper
     * @param \ReflectionProperty $property
     */
    public static function setLazyLoad(IDomainObject $object, IMapper $mapper, \ReflectionProperty $property)
    {
        if (!$property->isPublic()) {
            $property->setAccessible(true);
        }
        $property->setValue($object, new static($mapper, $property->getValue($object)->getId()));
    }
}