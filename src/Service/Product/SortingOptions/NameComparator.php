<?php

namespace Service\Product\SortingOptions;


use Model\Entity\Product;
use Service\Product\SortingOptions\Interfaces\IComparator;

class NameComparator implements IComparator
{
    /**
     * Выполняет сортировку по алфовиту по названию
     *
     * @param array $data
     * @return array
     */
    public function compare(array $data): array
    {
        usort($data, function ($a, $b) {
            /* @var Product $a */
            /* @var Product $b */
            return strcasecmp($a->getName(), $b->getName());

        });
        return $data;
    }

}