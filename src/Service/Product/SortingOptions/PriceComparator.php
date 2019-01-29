<?php

namespace Service\Product\SortingOptions;


use Model\Entity\Product;
use Service\Product\SortingOptions\Interfaces\IComparator;

class PriceComparator implements IComparator
{
    /**
     * Выполнят сортировку по возрастанию стоимости
     *
     * @param array $data
     * @return array
     */
    public function compare(array $data): array
    {
        usort($data, function ($a, $b) {
            /* @var Product $a */
            /* @var Product $b */
            if ($a->getPrice() === $b->getPrice()) {
                return 0;
            }
            return $a->getPrice() < $b->getPrice() ? -1 : 1;

        });
        return $data;
    }

}