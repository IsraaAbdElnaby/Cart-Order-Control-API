<?php

namespace Voo\CartOrderControl\Model;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Voo\CartOrderControl\Api\CartInterface;
use Magento\Catalog\Model\ProductFactory;

class Cart implements CartInterface
{

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var CartTotalRepositoryInterface
     */
    private $quoteIdMaskFactory;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    private $productFactory;


    public function __construct(
        CartRepositoryInterface $cartRepository,
        QuoteIdMaskFactory $quoteIdMaskFactory,
        ProductRepositoryInterface $productRepository,
        ProductFactory $productFactory
    ) {
        $this->cartRepository = $cartRepository;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->productRepository = $productRepository;
        $this->productFactory = $productFactory;
    }

    public function getCartDetails(int $cartId): array
    {
        $quote = $this->cartRepository->get($cartId);
        $items = [];

        foreach ($quote->getAllVisibleItems() as $item) {
            $items[] = [
                'sku' => $item->getSku(),
                'qty' => $item->getQty(),
                'price' => $item->getPrice(),
                'name' => $item->getName(),
            ];
        }

        return [
            'items' => $items,
            'totals' => [
                'subtotal' => $quote->getSubtotal(),
                'grand_total' => $quote->getGrandTotal(),
            ],
            'total_items' => count($items)
            ];
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function addOrUpdateItem(int $cartId, string $sku, float $quantity): bool
    {
        try {
            $quote = $this->cartRepository->getActive($cartId);
            $product = $this->productRepository->get($sku);

            if (!$product->getId()) {
                throw new NoSuchEntityException(__('Product not found.'));
            }

            $productModel = $this->productFactory->create();
            $productModel->load($product->getId());


            $cartItem = $quote->getItemByProduct($productModel);

            // Validate quantity
            if ($quantity <= 0) {
                if ($cartItem) {
                    // If item exists, remove it
                    $quote->removeItem($cartItem->getId());
                    $this->cartRepository->save($quote);
                } else {
                    return true;
                }
            }
            if ($cartItem) {
                $cartItem->setQty($quantity);
                $cartItem->getProduct()->isSalable(true);
                $quote->updateItem($cartItem->getId(), $cartItem);
            } else {
                $quote->addProduct($product, $quantity);
            }

            $this->cartRepository->save($quote);
            return true;
        } catch (NoSuchEntityException $e) {
            throw new LocalizedException(__('Could not find the product with SKU: %1', $sku));
        } catch (\Exception $e) {
            throw new LocalizedException(__('Could not add/update the product: %1', $e->getMessage()));
        }
    }

}
