<?php

namespace Service\DataMappers\Interfaces;


use Service\DbService\Interfaces\IDomainObject;

interface IMapper
{
    public function getById(int $id): ?IDomainObject;
}