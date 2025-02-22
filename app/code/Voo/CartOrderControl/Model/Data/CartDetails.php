<?php

namespace Voo\CartOrderControl\Model\Data;

use Magento\Framework\Api\AbstractExtensibleObject;
use Magento\Framework\DataObject;
use Voo\CartOrderControl\Api\Data\CartDetailsInterface;


class CartDetails extends AbstractExtensibleObject implements CartDetailsInterface
{

    /**
     * @inheritdoc
     */
    public function getItems() : array
    {
        return $this->_get(self::ITEMS);
    }

    /**
     * @inheritdoc
     * @param \Voo\CartOrderControl\Api\Data\CartItemInterface[] $items
     * @return CartDetailsInterface
     */
    public function setItems(array $items): CartDetailsInterface
    {
        return $this->setData(self::ITEMS, $items);
    }

    /**
     * @inheritdoc
     */
    public function getGrandTotal(): float
    {
        return (float)$this->_get(self::GRAND_TOTAL);
    }

    /**
     * @inheritdoc
     */
    public function setGrandTotal(float $grandTotal): CartDetailsInterface
    {
        return $this->setData(self::GRAND_TOTAL, $grandTotal);
    }

    /**
     * @inheritdoc
     */
    public function getSubtotal(): float
    {
        return (float)$this->_get(self::SUBTOTAL);
    }

    /**
     * @inheritdoc
     */
    public function setSubtotal(float $subTotal): CartDetailsInterface
    {
        return $this->setData(self::SUBTOTAL, $subTotal);
    }

    /**
     * @inheritdoc
     */
    public function getCouponCode(): ?string
    {
        $couponCode = $this->_get(self::COUPON_CODE);
        return $couponCode !== null ? (string)$couponCode : null;
    }

    /**
     * @inheritdoc
     */
    public function setCouponCode(?string $couponCode): CartDetailsInterface
    {
        return $this->setData(self::COUPON_CODE, $couponCode);
    }

    /**
     * @inheritdoc
     */
    public function getItemsQty(): float
    {
        return (float)$this->_get(self::ITEMS_QTY);
    }

    /**
     * @inheritdoc
     */
    public function setItemsQty(float $itemsQty): CartDetailsInterface
    {
        return $this->setData(self::ITEMS_QTY, $itemsQty);
    }

    /**
     * @inheritdoc
     */
    public function getCurrencyCode(): string
    {
        return (string)$this->_get(self::CURRENCY_CODE);
    }

    /**
     * @inheritdoc
     */
    public function setCurrencyCode(string $currencyCode): CartDetailsInterface
    {
        return $this->setData(self::CURRENCY_CODE, $currencyCode);
    }

    /**
     * @inheritdoc
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * @inheritdoc
     */
    public function setExtensionAttributes(
        \Voo\CartOrderControl\Api\Data\CartDetailsExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
