<?php

namespace Framework\Commands;

use Framework\Commands\Interfaces\ICommand;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class CommandRegisterConfig implements ICommand
{
    public function execute(array $params): array
    {
        try {
            $fileLocator = new FileLocator($params['appDir'] . DIRECTORY_SEPARATOR . 'config');
            $loader = new PhpFileLoader($params['container'], $fileLocator);
            $loader->load('parameters.php');
        } catch (\Throwable $e) {
            die('Cannot read the config file. File: ' . __FILE__ . '. Line: ' . __LINE__);
        }

        return $params;
    }
}
