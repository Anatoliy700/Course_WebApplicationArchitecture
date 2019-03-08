<?php

namespace Model\Repository;


class RoleRepository
{

    /**
     * Получаем роль по идентификатору
     *
     * @param int $id
     * @return array|null
     */
    public function getById(int $id): ?array
    {
        foreach ($this->getDataFromSource(['id' => $id]) as $role) {
            return $role;
        }

        return null;
    }

    /**
     * Получаем роли из источника данных
     *
     * @param array $search
     *
     * @return array
     */
    private function getDataFromSource(array $search = [])
    {

        $dataSource = [
            [
                'id' => 1,
                'title' => 'Super Admin',
                'type' => 'admin'
            ],
            [
                'id' => 2,
                'title' => 'Main user',
                'type' => 'user'
            ],
            [
                'id' => 3,
                'title' => 'For test needed',
                'type' => 'test'
            ],
        ];

        if (!count($search)) {
            return $dataSource;
        }

        $productFilter = function (array $dataSource) use ($search): bool {
            return (bool)array_intersect($dataSource, $search);
        };

        return array_filter($dataSource, $productFilter);
    }
}