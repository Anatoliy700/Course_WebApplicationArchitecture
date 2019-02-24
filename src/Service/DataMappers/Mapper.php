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

}