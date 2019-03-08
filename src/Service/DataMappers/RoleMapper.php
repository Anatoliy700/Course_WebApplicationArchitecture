<?php

namespace Service\DataMappers;


use Model\Entity\Role;
use Model\Repository\RoleRepository;
use Service\DbService\Exceptions\EmptyCacheException;
use Service\DbService\Interfaces\IDomainObject;

class RoleMapper extends Mapper
{
    /**
     * @return string
     */
    protected function getEntityClass(): string
    {
        return Role::class;
    }

    /**
     * @param int $id
     * @return Role|\Service\DbService\Interfaces\IDomainObject|null
     * @throws \Exception
     */
    public function getById(int $id): ?IDomainObject
    {
        try {
            $role = $this->getFromCache($id);
        } catch (EmptyCacheException $e) {

            $roleData = $this->adapter->find([
                'class' => RoleRepository::class,
                'method' => [
                    'name' => 'getById',
                    'params' => [
                        $id
                    ]
                ]
            ]);

            if (!$roleData) {
                throw new \Exception("Роль с ID {$id} не найдена");
            }

            $role = $this->objectFactory($roleData);

            $this->setInCache($role);

        } finally {
            return $role;
        }
    }
}