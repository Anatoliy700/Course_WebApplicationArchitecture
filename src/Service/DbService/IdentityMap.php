<?php

namespace Service\DbService;


use Service\DbService\Exceptions\EmptyCacheException;
use Service\DbService\Interfaces\IDomainObject;
use Traits\Singleton;

class IdentityMap
{

    use Singleton;

    /**
     * @var array
     */
    private $objects = [];

    /**
     * @param IDomainObject $obj
     */
    public function add(IDomainObject $obj)
    {
        $key = $this->getGlobalKey(get_class($obj), $obj->getId());
        $this->objects[$key] = $obj;
    }

    /**
     * @param string $classname
     * @param int $id
     * @return mixed
     * @throws EmptyCacheException
     */
    public function get(string $classname, int $id)
    {
        $key = $this->getGlobalKey($classname, $id);
        if (isset($this->objects[$key])) {
            return $this->objects[$key];
        }
        throw new EmptyCacheException();
    }

    /**
     * @param string $classname
     * @param int $id
     * @return string
     */
    private function getGlobalKey(string $classname, int $id)
    {
        return sprintf('%s.%d', $classname, $id);
    }
}