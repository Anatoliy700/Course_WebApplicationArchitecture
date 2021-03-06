<?php

namespace Service\DataMappers;


use Exception;
use Model\Entity\User;
use Service\DbService\Exceptions\EmptyCacheException;
use Service\DbService\Interfaces\IDomainObject;

class UserMapper extends Mapper
{
    /**
     * @return string
     */
    protected function getEntityClass(): string
    {
        return User::class;
    }

    /**
     * @param int $id
     * @return User|null
     * @throws Exception
     */
    public function getById(int $id): ?IDomainObject
    {
        try {

            $user = $this->getFromCache($id);

        } catch (EmptyCacheException $e) {

            $userData = $this->adapter->find([
                'class' => \Model\Repository\UserRepository::class,
                'method' => [
                    'name' => 'getById',
                    'params' => [
                        $id
                    ]
                ]
            ]);

            if (!$userData) {
                throw new Exception("Пользователь с ID {$id} не найден");
            }

            $user = $this->objectFactory($userData);

            $this->setInCache($user);

        } finally {
            return $user;
        }
    }

    /**
     * @param string $login
     * @return User|null
     * @throws Exception
     */
    public function getByLogin(string $login): ?User
    {
        $userId = $this->adapter->find([
            'class' => \Model\Repository\UserRepository::class,
            'method' => [
                'name' => 'getIdByLogin',
                'params' => [
                    $login
                ]
            ]
        ]);

        if (!$userId) {
            throw new Exception("Пользователь с Login {$login} не найден");
        }

        return $this->getById($userId[0]);
    }
}