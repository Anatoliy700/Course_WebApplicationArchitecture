<?php

namespace Service\Order;


use Service\Billing\Card;
use Service\Commands\Interfaces\IBasketCheckout;
use Service\Commands\Interfaces\ICheckout;
use Service\Communication\Email;
use Service\Discount\NullObject;
use Service\User\ISecurity;

class Checkout
{
    private $basket;
    private $security;
    private $command;

    /**
     * Checkout constructor.
     * @param ISecurity $security
     * @param IBasketCheckout $basket
     * @param ICheckout $command
     */
    public function __construct(
        ISecurity $security,
        IBasketCheckout $basket,
        ICheckout $command
    )
    {
        $this->basket = $basket;
        $this->security = $security;
        $this->command = $command;
    }

    /**
     * Оформление заказа
     */
    public function execute()
    {
        // Здесь должна быть некоторая логика выбора способа платежа
        $billing = new Card();

        // Здесь должна быть некоторая логика получения информации о скидки пользователя
        $discount = new NullObject();

        // Здесь должна быть некоторая логика получения способа уведомления пользователя о покупке
        $communication = new Email();

        $this->command->checkout(
            $this->security,
            $this->basket,
            $billing,
            $discount,
            $communication
        );
    }
}