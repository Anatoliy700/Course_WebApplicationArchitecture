<?php

/** @var $container \Symfony\Component\DependencyInjection\ContainerBuilder */

use Symfony\Component\DependencyInjection\Reference;


$container->register('session', \Symfony\Component\HttpFoundation\Session\Session::class);

$container->register('repository.basket_session', \Model\Repository\BasketSession::class)
    ->addArgument(new Reference('session'));

$container->register('order.basket', \Service\Order\Basket::class)
    ->addArgument(new Reference('repository.basket_session'));

$container->register('user.security', \Service\User\Security::class)
    ->addArgument(new Reference('session'));

$container->register('order.checkout', \Service\Order\Checkout::class)
    ->addArgument(new Reference('user.security'))
    ->addArgument(new Reference('order.basket'));