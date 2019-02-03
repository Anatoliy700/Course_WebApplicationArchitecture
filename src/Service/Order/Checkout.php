<?php

namespace Service\Order;


use Service\Billing\Card;
use Service\Commands\Interfaces\IBasketCheckout;
use Service\Communication\Email;
use Service\Discount\NullObject;
use Service\User\ISecurity;

class Checkout
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
     * Оформление заказа
     */
    public function execute()
    {
        $builder = new CheckoutProcessBuilder();

        // Здесь должна быть некоторая логика выбора способа платежа
        $builder->setBilling(new Card());

        // Здесь должна быть некоторая логика получения информации о скидки пользователя
        $builder->setDiscount(new NullObject());

        // Здесь должна быть некоторая логика получения способа уведомления пользователя о покупке
        $builder->setCommunication(new Email());

        $builder->setBasket($this->basket);

        $builder->setSecurity($this->security);

        $checkoutProcessCommand = $builder->build();

        $checkoutProcessCommand->checkout();
    }
}