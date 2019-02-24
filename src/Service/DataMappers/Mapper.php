<?php

namespace Service\DataMappers;

use Service\Adapters\Interfaces\IDbAdapter;


abstract class Mapper
{
    /**
     * @var IDbAdapter
     */
    protected $adapter;

    /**
     * UserMapper constructor.
     * @param IDbAdapter $adapter
     */
    public function __construct(IDbAdapter $adapter)
    {
        $this->adapter = $adapter;
    }
}