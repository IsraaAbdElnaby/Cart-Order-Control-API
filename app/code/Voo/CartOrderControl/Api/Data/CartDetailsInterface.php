<?php

namespace Voo\CartOrderControl\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface CartDetailsInterface extends ExtensibleDataInterface
{

    /**
     * Constants for keys of data array
     */
    const ITEMS = 'items';
    const GRAND_TOTAL = 'grand_total';
    const SUBTOTAL = 'subtotal';
    const COUPON_CODE = 'coupon_code';
    const ITEMS_QTY = 'items_qty';
    const CURRENCY_CODE = 'currency_code';

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

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Voo\CartOrderControl\Api\Data\CartDetailsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Voo\CartOrderControl\Api\Data\CartDetailsExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Voo\CartOrderControl\Api\Data\CartDetailsExtensionInterface $extensionAttributes
    );

}
