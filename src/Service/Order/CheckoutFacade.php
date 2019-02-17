<?php

namespace Service\Order;


use Service\Billing\IBilling;
use Service\Commands\Interfaces\IBasketCheckout;
use Service\Communication\ICommunication;
use Service\Discount\IDiscount;
use Service\User\ISecurity;

class CheckoutFacade
{
    /**
     * @var IBasketCheckout
     */
    private $basket;

    /**
     * @var ISecurity
     */
    private $security;

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
     * Checkout constructor.
     * @param ISecurity $security
     * @param IBasketCheckout $basket
     */
    public function __construct(
        ISecurity $security,
        IBasketCheckout $basket
    )
    {
        $this->basket = $basket;
        $this->security = $security;
    }

    /**
     * @param IBilling $billing
     */
    public function setBilling(IBilling $billing): void
    {
        $this->billing = $billing;
    }

    /**
     * @param IDiscount $discount
     */
    public function setDiscount(IDiscount $discount): void
    {
        $this->discount = $discount;
    }

    /**
     * @param ICommunication $communication
     */
    public function setCommunication(ICommunication $communication): void
    {
        $this->communication = $communication;
    }

    /**
     * Оформление заказа
     */
    public function checkout()
    {
        $builder = new CheckoutProcessBuilder();

        // Здесь должна быть некоторая логика выбора способа платежа
        $builder->setBilling($this->billing);

        // Здесь должна быть некоторая логика получения информации о скидки пользователя
        $builder->setDiscount($this->discount);

        // Здесь должна быть некоторая логика получения способа уведомления пользователя о покупке
        $builder->setCommunication($this->communication);

        $builder->setBasket($this->basket);

        $builder->setSecurity($this->security);

        $checkoutProcessCommand = $builder->build();

        $checkoutProcessCommand->checkout();
    }
}