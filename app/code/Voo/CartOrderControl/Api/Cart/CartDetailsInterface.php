<?php

namespace Voo\CartOrderControl\Api\Cart;

interface CartDetailsInterface
{
    /**
     * @return CartItemInterface[]
     */
    public function getItems(): array;

    /**
     * @param CartItemInterface[] $items
     * @return $this
     */
    public function setItems(array $items): self;

    /**
     * @return float
     */
    public function getGrandTotal(): float;

    /**
     * @param float $grandTotal
     * @return $this
     */
    public function setGrandTotal(float $grandTotal): self;

    /**
     * @return float
     */
    public function getSubTotal(): float;

    /**
     * @param float $subTotal
     * @return $this
     */
    public function setSubTotal(float $subTotal): self;

    /**
     * @return string|null
     */
    public function getCouponCode(): ?string;

    /**
     * @param string|null $couponCode
     * @return $this
     */
    public function setCouponCode(?string $couponCode): self;

    /**
     * @return float
     */
    public function getItemsQty(): float;

    /**
     * @param float $itemsQty
     * @return $this
     */
    public function setItemsQty(float $itemsQty): self;

    /**
     * @return string
     */
    public function getCurrencyCode(): string;

    /**
     * @param string $currencyCode
     * @return $this
     */
    public function setCurrencyCode(string $currencyCode): self;

}
