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

    public function checkout()
    {
        $checkout = new CheckoutFacade($this->security, $this->basket);

        // Здесь должна быть некоторая логика выбора способа платежа
        $checkout->setBilling(new Card());

        // Здесь должна быть некоторая логика получения информации о скидки пользователя
        $checkout->setDiscount(new NullObject());

        // Здесь должна быть некоторая логика получения способа уведомления пользователя о покупке
        $checkout->setCommunication(new Email());

        // Запуск процесса
        $checkout->checkout();
    }


}