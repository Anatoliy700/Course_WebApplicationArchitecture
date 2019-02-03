<?php

namespace Service\Order;


use Service\Billing\IBilling;
use Service\Commands\CheckoutProcess;
use Service\Commands\Interfaces\IBasketCheckout;
use Service\Communication\ICommunication;
use Service\Discount\IDiscount;
use Service\User\ISecurity;

class CheckoutProcessBuilder
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
     * @return ISecurity
     */
    public function getSecurity(): ISecurity
    {
        return $this->security;
    }

    /**
     * @param ISecurity $security
     * @return CheckoutProcessBuilder
     */
    public function setSecurity(ISecurity $security): self
    {
        $this->security = $security;
        return $this;
    }

    /**
     * @return IBasketCheckout
     */
    public function getBasket(): IBasketCheckout
    {
        return $this->basket;
    }

    /**
     * @param IBasketCheckout $basket
     * @return CheckoutProcessBuilder
     */
    public function setBasket(IBasketCheckout $basket): self
    {
        $this->basket = $basket;
        return $this;
    }

    /**
     * @return IBilling
     */
    public function getBilling(): IBilling
    {
        return $this->billing;
    }

    /**
     * @param IBilling $billing
     * @return CheckoutProcessBuilder
     */
    public function setBilling(IBilling $billing): self
    {
        $this->billing = $billing;
        return $this;
    }

    /**
     * @return IDiscount
     */
    public function getDiscount(): IDiscount
    {
        return $this->discount;
    }

    /**
     * @param IDiscount $discount
     * @return CheckoutProcessBuilder
     */
    public function setDiscount(IDiscount $discount): self
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * @return ICommunication
     */
    public function getCommunication(): ICommunication
    {
        return $this->communication;
    }

    /**
     * @param ICommunication $communication
     * @return CheckoutProcessBuilder
     */
    public function setCommunication(ICommunication $communication): self
    {
        $this->communication = $communication;
        return $this;
    }

    /**
     * @return CheckoutProcess
     */
    public function build(): CheckoutProcess
    {
        return new CheckoutProcess($this);
    }
}