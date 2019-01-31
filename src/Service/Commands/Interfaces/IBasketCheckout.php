<?php

namespace Service\Commands\Interfaces;


interface IBasketCheckout
{
    public function getTotalPrice(): float;
}