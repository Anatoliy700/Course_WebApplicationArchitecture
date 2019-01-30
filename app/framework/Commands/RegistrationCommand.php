<?php

namespace Framework\Commands;


use Framework\Commands\Interfaces\ICommand;
use Framework\Commands\Receivers\Interfaces\IRegistryReceiver;

class RegistrationCommand implements ICommand
{
    /**
     * @var IRegistryReceiver
     */
    private $receiver;

    /**
     * RegistrationCommand constructor.
     * @param IRegistryReceiver $receiver
     */
    public function __construct(IRegistryReceiver $receiver)
    {
        $this->receiver = $receiver;
    }

    /**
     * Запуск команды
     */
    public function execute(): void
    {
        $this->receiver->registry();
    }

}