<?php

namespace Framework\Commands;

use Framework\Commands\Interfaces\ICommand;

class CommandRegisterRoutes implements ICommand
{
    public function execute(array $params): array
    {
        $routeCollection = require $params['appDir'] . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'routing.php';
        $params['container']->set('route_collection', $routeCollection);

        return $params;
    }
}
