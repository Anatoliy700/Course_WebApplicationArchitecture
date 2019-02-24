<?php

namespace Service\Adapters\Interfaces;


interface IDbAdapter
{
    public function find(array $params): ?array;

}