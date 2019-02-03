<?php

namespace Service\Commands;


use Service\Billing\IBilling;
use Service\Commands\Interfaces\IBasketCheckout;
use Service\Commands\Interfaces\ICheckout;
use Service\Communication\ICommunication;
use Service\Discount\IDiscount;
use Service\Order\CheckoutProcessBuilder;
use Service\User\ISecurity;

class CheckoutProcess implements ICheckout
{
    /**
     * @var ISecurity
     */
    private $security;

    /**
     * @var IBasketCheckout
     */
    private $basket;

    /**
     * @var IBilling
     */
    private $billing;

    /**
     * @var IDiscount
     */
    private $discount;

    /**
     * @var ICommunication
     */
    private $communication;

    /**
     * CheckoutProcess constructor.
     * @param CheckoutProcessBuilder $builder
     */
    public function __construct(CheckoutProcessBuilder $builder)
    {
        $this->security = $builder->getSecurity();
        $this->basket = $builder->getBasket();
        $this->billing = $builder->getBilling();
        $this->discount = $builder->getDiscount();
        $this->communication = $builder->getCommunication();
    }


    /**
     * @throws \Service\Billing\Exception\BillingException
     * @throws \Service\Communication\Exception\CommunicationException
     */
    public function checkout()
    {
        $totalPrice = $this->basket->getTotalPrice();

        $discount = $this->discount->getDiscount();
        $totalPrice = $totalPrice - $totalPrice / 100 * $discount;

        $this->billing->pay($totalPrice);

        $user = $this->security->getUser();
        $this->communication->process($user, 'checkout_template');
    }

}