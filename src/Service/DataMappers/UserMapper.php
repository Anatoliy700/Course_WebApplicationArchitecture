<?php

namespace Service\DataMappers;


use Exception;
use Model\Entity\Role;
use Model\Entity\User;

class UserMapper extends Mapper
{
    /**
     * @param int $id
     * @return User
     * @throws Exception
     */
    public function getById(int $id): User
    {
        $data = $this->adapter->find([
            'class' => \Model\Repository\UserRepository::class,
            'params' => [
                [
                    'method' => 'getById',
                    'value' => $id
                ]
            ]
        ]);

        if (!$data) {
            throw new Exception("Пользователь с ID {$id} не найден");
        }

        return $this->UserFactory($data);
    }

    /**
     * @param string $login
     * @return User
     * @throws Exception
     */
    public function getByLogin(string $login): User
    {
        $data = $this->adapter->find([
            'class' => \Model\Repository\UserRepository::class,
            'params' => [
                [
                    'method' => 'getByLogin',
                    'value' => $login
                ]
            ]
        ]);

        if (!$data) {
            throw new Exception("Пользователь с Login {$login} не найден или не верный пароль");
        }

        return $this->UserFactory($data);
    }

    /**
     * @param array $user
     * @return User
     */
    protected function UserFactory(array $user): User
    {
        $role = $user['role'];

        return new User(
            $user['id'],
            $user['name'],
            $user['login'],
            $user['password'],
            new Role($role['id'], $role['title'], $role['role'])
        );
    }
}