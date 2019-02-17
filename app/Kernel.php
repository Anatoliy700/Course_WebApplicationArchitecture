<?php

declare(strict_types = 1);

use Framework\Commands\CommandRegisterConfig;
use Framework\Commands\CommandRegisterRoutes;
use Framework\Commands\CommandRegisterServices;
use Framework\ControllerFacade;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Kernel
{
    /**
     * @var ContainerBuilder
     */
    static $container;

    /**
     * @var array
     */
    protected $params;

    public function __construct(ContainerBuilder $containerBuilder)
    {
        static::$container = $containerBuilder;

        $this->params = [
            'appDir' => __DIR__,
            'container' => $containerBuilder
        ];

        $containerBuilder->register(\Service\Discount\NullObject::class);
    }

    /**
     * @param Request $request
     * @return Response
     */
    final public function handle(Request $request): Response
    {
        $this->params['request'] = $request;

        (new CommandRegisterConfig())->execute($this->params);

        (new CommandRegisterRoutes())->execute($this->params);

        (new CommandRegisterServices())->execute($this->params);

        return (new ControllerFacade())->getResponse($this->params);
    }
}
