<?php

namespace Service\Commands\Interfaces;


use Service\Billing\IBilling;
use Service\Communication\ICommunication;
use Service\Discount\IDiscount;
use Service\User\ISecurity;

interface ICheckout
{
    public function checkout(
        ISecurity $security,
        IBasketCheckout $basket,
        IBilling $billing,
        IDiscount $discount,
        ICommunication $communication
    );
}