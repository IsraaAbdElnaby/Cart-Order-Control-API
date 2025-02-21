<?php

namespace Voo\CartOrderControl\Api;


interface OrderInterface
{
    /**
     * Submit current cart as order
     *
     * @param int $cartId
     * @param int $customerId
     * @return array
     */
    public function submitOrder(int $cartId, int $customerId): array;

}
