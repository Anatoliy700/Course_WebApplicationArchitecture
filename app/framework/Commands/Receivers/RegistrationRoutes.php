<?php

namespace Framework\Commands\Receivers;


use Framework\Commands\Receivers\Interfaces\IRegistryReceiver;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegistrationRoutes implements IRegistryReceiver
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var string
     */
    private $resourcePath;

    /**
     * RegistrationRoutes constructor.
     * @param ContainerBuilder $container
     * @param $resourcePath
     */
    public function __construct(ContainerBuilder $container, $resourcePath)
    {
        $this->container = $container;
        $this->resourcePath = $resourcePath;
    }

    /**
     * Реристрация маршрутов
     */
    public function registry(): void
    {
        $routeCollection = require $this->resourcePath;
        $this->container->set('route_collection', $routeCollection);
    }

}