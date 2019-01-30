<?php

namespace Framework\Commands\Receivers;


use Framework\Commands\Receivers\Interfaces\IRegistryReceiver;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class RegistrationConfigs implements IRegistryReceiver
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var string
     */
    private $configDir;

    /**
     * @var string
     */
    private $resource;

    /**
     * RegistrationConfigs constructor.
     * @param ContainerBuilder $container
     * @param $configDir
     * @param $resource
     */
    public function __construct(ContainerBuilder $container, $configDir, $resource)
    {
        $this->container = $container;
        $this->configDir = $configDir;
        $this->resource = $resource;
    }

    /**
     * Регистрация конфигурации
     */
    public function registry(): void
    {
        try {
            $fileLocator = new FileLocator($this->configDir);
            $loader = new PhpFileLoader($this->container, $fileLocator);
            $loader->load($this->resource);
        } catch (\Throwable $e) {
            die('Cannot read the config file. File: ' . __FILE__ . '. Line: ' . __LINE__);
        }
    }
}