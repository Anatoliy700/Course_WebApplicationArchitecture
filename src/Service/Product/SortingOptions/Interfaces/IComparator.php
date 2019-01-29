<?php

namespace Service\Product\SortingOptions\Interfaces;


interface IComparator
{
    public function compare(array $data): array;
}