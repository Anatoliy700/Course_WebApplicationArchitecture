<?php

namespace Framework\Commands;


use Framework\Commands\Interfaces\ICommand;

class RegistrationInvoker
{

    /**
     * @var ICommand
     */
    private $command;

    /**
     * RegistrationInvoker constructor.
     * @param ICommand $command
     */
    public function __construct(ICommand $command)
    {
        $this->command = $command;
    }

    /**
     * Запуск команды
     */
    public function register()
    {
        $this->command->execute();
    }

}