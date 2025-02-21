<?php

namespace Voo\CartOrderControl\Api;

use Voo\CartOrderControl\Api\Cart\CartDetailsInterface;

interface CartInterface
{
    /**
     * Get cart details for the current user
     *
     * @param int $cartId
     * @return CartDetailsInterface
     */
    public function getCartDetails(int $cartId): CartDetailsInterface;

    /**
     * Add or update items in cart
     *
     * @param int $cartId
     * @param string $sku
     * @param int $quantity
     * @return bool
     */
    public function addOrUpdateItem(int $cartId, string $sku, int $quantity): bool;
}
