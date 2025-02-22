<?php

namespace Voo\CartOrderControl\Api;

use Voo\CartOrderControl\Api\Data\CartDetailsInterface;

interface CartInterface
{
    /**
     * Get cart details for the current user
     *
     * @param int $cartId
     * @return array
     */
    public function getCartDetails(int $cartId): array;

    /**
     * Add or update items in cart
     *
     * @param int $cartId
     * @param string $sku
     * @param float $quantity
     * @return bool
     */
    public function addOrUpdateItem(int $cartId, string $sku, float $quantity): bool;
}
