<?php

namespace Voo\CartOrderControl\Model;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use Voo\CartOrderControl\Api\Data\CartDetailsInterface;
use Voo\CartOrderControl\Api\Data\CartDetailsMapperInterface;
use Voo\CartOrderControl\Api\CartInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Checkout\Model\Session as CheckoutSession;

class Cart implements CartInterface
{

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var CartTotalRepositoryInterface
     */
    private $cartTotalRepository;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var CartDetailsMapperInterface
     */
    private $cartDetailsMapper;

    private $customerSession;

    private $checkoutSession;

    public function __construct(
        CartRepositoryInterface $cartRepository,
        CartTotalRepositoryInterface $cartTotalRepository,
        ProductRepositoryInterface $productRepository,
        CartDetailsMapperInterface $cartDetailsMapper,
        CustomerSession $customerSession,
        CheckoutSession $checkoutSession
    ) {
        $this->cartRepository = $cartRepository;
        $this->cartTotalRepository = $cartTotalRepository;
        $this->productRepository = $productRepository;
        $this->cartDetailsMapper = $cartDetailsMapper;
        $this->customerSession = $customerSession;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @inheritDoc
     * @throws NoSuchEntityException
     */
    public function getCartDetails(): CartDetailsInterface
    {
        if(!$this->customerSession->isLoggedIn()) {
            throw new NoSuchEntityException(__('You are not logged in.'));
        }

        $quote = $this->checkoutSession->getQuote();
        if (!$quote || !$quote->getId()) {
            throw new NoSuchEntityException(__('Cart does not exist.'));
        }
        return $this->cartDetailsMapper->map($quote);
    }

    /**
     * @inheritDoc
     * @throws LocalizedException
     */
    public function addOrUpdateItem(int $cartId, string $sku, int $quantity): bool
    {
        $quote = $this->cartRepository->getActive($cartId);
        $product = $this->productRepository->get($sku);

        if ($quote->getId()) {
            $cartItem = null;

            foreach ($quote->getAllItems() as $item) {
                if ($item->getSku() === $sku) {
                    $cartItem = $item;
                    break;
                }
            }

            //If the item exists in the cart update its quantity, else add it
            if ($cartItem) {
                $cartItem->setQty($quantity);
            } else {
                $cartItem = $quote->addProduct($product, $quantity);
                if (is_string($cartItem)) {
                    throw new LocalizedException(__($cartItem));
                }
            }

            $this->cartRepository->save($quote);
            return true;
        }

        return false;
    }
}
