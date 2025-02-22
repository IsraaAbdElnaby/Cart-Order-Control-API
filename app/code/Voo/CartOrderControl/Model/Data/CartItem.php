<?php

namespace Voo\CartOrderControl\Model\Data;

use Magento\Framework\DataObject;
use Magento\Framework\Api\AbstractExtensibleObject;
use Voo\CartOrderControl\Api\Data\CartItemInterface;

class CartItem extends AbstractExtensibleObject implements CartItemInterface
{
    /**
     * @inheritdoc
     */
    public function getSku(): string
    {
        return (string)$this->_get('sku');
    }

    /**
     * @inheritdoc
     */
    public function setSku(string $sku): CartItemInterface
    {
        return $this->setData('sku', $sku);
    }

    /**
     * @inheritdoc
     */
    public function getQty(): float
    {
        return (float)$this->_get('qty');
    }

    /**
     * @inheritdoc
     */
    public function setQty(float $qty): CartItemInterface
    {
        return $this->setData('qty', $qty);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return (string)$this->_get('name');
    }

    /**
     * @inheritdoc
     */
    public function setName(string $name): CartItemInterface
    {
        return $this->setData('name', $name);
    }

    /**
     * @inheritdoc
     */
    public function getPrice(): float
    {
        return (float)$this->_get('price');
    }

    /**
     * @inheritdoc
     */
    public function setPrice(float $price): CartItemInterface
    {
        return $this->setData('price', $price);
    }

    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    public function setExtensionAttributes(\Voo\CartOrderControl\Api\Data\CartItemExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
