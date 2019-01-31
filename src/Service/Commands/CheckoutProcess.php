<?php

namespace Service\Commands;


use Service\Billing\IBilling;
use Service\Commands\Interfaces\IBasketCheckout;
use Service\Commands\Interfaces\ICheckout;
use Service\Communication\ICommunication;
use Service\Discount\IDiscount;
use Service\User\ISecurity;

class CheckoutProcess implements ICheckout
{
    /**
     * Проведение всех этапов заказа
     *
     * @param IDiscount $discount
     * @param IBilling $billing
     * @param ISecurity $security
     * @param ICommunication $communication
     * @param IBasketCheckout $basket
     * @throws \Service\Billing\Exception\BillingException
     * @throws \Service\Communication\Exception\CommunicationException
     */
    public function checkout(
        ISecurity $security,
        IBasketCheckout $basket,
        IBilling $billing,
        IDiscount $discount,
        ICommunication $communication
    )
    {
        $totalPrice = $basket->getTotalPrice();

        $discount = $discount->getDiscount();
        $totalPrice = $totalPrice - $totalPrice / 100 * $discount;

        $billing->pay($totalPrice);

        $user = $security->getUser();
        $communication->process($user, 'checkout_template');
    }

}