<?php

declare(strict_types = 1);

namespace Controller;

use Framework\Render;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController
{
    use Render;

    /**
     * Корзина
     *
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function infoAction(Request $request): Response
    {
        if ($request->isMethod(Request::METHOD_POST)) {
            return $this->redirect('order_checkout');
        }

        $productList = (\Kernel::$container->get('order.basket'))->getProductsInfo();
        $isLogged = (\Kernel::$container->get('user.security'))->isLogged();

        return $this->render('order/info.html.php', ['productList' => $productList, 'isLogged' => $isLogged]);
    }

    /**
     * Оформление заказа
     *
     * @return Response
     * @throws \Exception
     */
    public function checkoutAction(): Response
    {
        $isLogged = (\Kernel::$container->get('user.security'))->isLogged();
        if (!$isLogged) {
            return $this->redirect('user_authentication');
        }

        \Kernel::$container->get('order.checkout')->checkout();

        return $this->render('order/checkout.html.php');
    }
}
