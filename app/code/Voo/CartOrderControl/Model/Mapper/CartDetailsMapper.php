<?php
declare(strict_types=1);

namespace Voo\CartOrderControl\Model\Mapper;

use Magento\Quote\Model\Quote;
use Voo\CartOrderControl\Api\Data\CartDetailsInterface;
use Voo\CartOrderControl\Api\Data\CartDetailsInterfaceFactory;
use Voo\CartOrderControl\Api\Data\CartItemInterface;
use Voo\CartOrderControl\Api\Data\CartItemInterfaceFactory;
use Voo\CartOrderControl\Api\Data\CartDetailsExtensionFactory;
use Voo\CartOrderControl\Api\Data\CartItemExtensionFactory;
use Voo\CartOrderControl\Api\Data\CartDetailsMapperInterface;
use Magento\Catalog\Helper\Image;
use Magento\CatalogInventory\Api\StockRegistryInterface;

class CartDetailsMapper implements CartDetailsMapperInterface
{
    /**
     * @var CartDetailsInterfaceFactory
     */
    private $cartDetailsFactory;

    /**
     * @var CartItemInterfaceFactory
     */
    private $cartItemFactory;

    /**
     * @var CartDetailsExtensionFactory
     */
    private $cartDetailsExtensionFactory;

    /**
     * @var CartItemExtensionFactory
     */
    private $cartItemExtensionFactory;

    /**
     * @var Image
     */
    private $imageHelper;

    /**
     * @var StockRegistryInterface
     */
    private $stockRegistry;

    /**
     * @param CartDetailsInterfaceFactory $cartDetailsFactory
     * @param CartItemInterfaceFactory $cartItemFactory
     * @param CartDetailsExtensionFactory $cartDetailsExtensionFactory
     * @param CartItemExtensionFactory $cartItemExtensionFactory
     * @param Image $imageHelper
     * @param StockRegistryInterface $stockRegistry
     */
    public function __construct(
        CartDetailsInterfaceFactory $cartDetailsFactory,
        CartItemInterfaceFactory $cartItemFactory,
        CartDetailsExtensionFactory $cartDetailsExtensionFactory,
        CartItemExtensionFactory $cartItemExtensionFactory,
        Image $imageHelper,
        StockRegistryInterface $stockRegistry
    ) {
        $this->cartDetailsFactory = $cartDetailsFactory;
        $this->cartItemFactory = $cartItemFactory;
        $this->cartDetailsExtensionFactory = $cartDetailsExtensionFactory;
        $this->cartItemExtensionFactory = $cartItemExtensionFactory;
        $this->imageHelper = $imageHelper;
        $this->stockRegistry = $stockRegistry;
    }

    /**
     * @inheritdoc
     */
    public function map(Quote $quote): CartDetailsInterface
    {
        /** @var CartDetailsInterface $cartDetails */
        $cartDetails = $this->cartDetailsFactory->create();

        // Map basic cart details with proper type casting
        $cartDetails->setGrandTotal((float)$quote->getGrandTotal())
            ->setSubtotal((float)$quote->getSubtotal())
            ->setCouponCode((string)$quote->getCouponCode())
            ->setItemsQty((float)$quote->getItemsQty())
            ->setCurrencyCode((string)$quote->getQuoteCurrencyCode());

        // Map items
        $items = [];
        foreach ($quote->getAllVisibleItems() as $quoteItem) {
            $items[] = $this->mapQuoteItemToCartItem($quoteItem);
        }
        $cartDetails->setItems($items);

        return $cartDetails;
    }

    /**
     * Map quote item to cart item
     *
     * @param \Magento\Quote\Model\Quote\Item $quoteItem
     * @return CartItemInterface
     */
    private function mapQuoteItemToCartItem(\Magento\Quote\Model\Quote\Item $quoteItem): CartItemInterface
    {
        /** @var CartItemInterface $cartItem */
        $cartItem = $this->cartItemFactory->create();

        // Map basic item data
        $cartItem->setSku((string)$quoteItem->getSku())
            ->setQty((float)$quoteItem->getQty())
            ->setName((string)$quoteItem->getName())
            ->setPrice((float)$quoteItem->getPrice());


        // Get product for additional data
        $product = $quoteItem->getProduct();

        // Create and set extension attributes
        $itemExtension = $cartItem->getExtensionAttributes() ?:
            $this->cartItemExtensionFactory->create();

        if ($product !== null) {
            // Add product image
            $imageUrl = $this->imageHelper->init($product, 'product_thumbnail_image')
                ->setImageFile($product->getThumbnail())
                ->getUrl();
            $itemExtension->setImageUrl($imageUrl);

            // Add stock information
            $stockItem = $this->stockRegistry->getStockItem(
                $product->getId(),
                $product->getStore()->getWebsiteId()
            );

            if ($stockItem) {
                $itemExtension->setIsInStock((bool)$stockItem->getIsInStock());
                $itemExtension->setQtyAvailable((float)$stockItem->getQty());
            }

            // Add product type
            $itemExtension->setProductType((string)$product->getTypeId());
        }

        $cartItem->setExtensionAttributes($itemExtension);

        return $cartItem;
    }
}
