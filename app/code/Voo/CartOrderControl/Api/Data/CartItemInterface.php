<?php

namespace Voo\CartOrderControl\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface CartItemInterface extends ExtensibleDataInterface
{
    /**
     * Constants for keys of data array
     */
    const SKU = 'sku';
    const QTY = 'qty';
    const NAME = 'name';
    const PRICE = 'price';
    const ROW_TOTAL = 'row_total';

    /**
     * @return string
     */
    public function getSku(): string;

    /**
     * @param string $sku
     * @return $this
     */
    public function setSku(string $sku): self;

    /**
     * @return float
     */
    public function getQty(): float;

    /**
     * @param float $qty
     * @return $this
     */
    public function setQty(float $qty): self;

    /**
     * @return float
     */
    public function getPrice(): float;

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice(float $price): self;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self;

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Voo\CartOrderControl\Api\Data\CartItemExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Voo\CartOrderControl\Api\Data\CartItemExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Voo\CartOrderControl\Api\Data\CartItemExtensionInterface $extensionAttributes
    );
}
