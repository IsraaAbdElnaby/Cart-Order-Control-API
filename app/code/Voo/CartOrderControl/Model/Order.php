<?php

namespace Voo\CartOrderControl\Model;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\QuoteManagement;
use Magento\Sales\Model\OrderRepository;
use Voo\CartOrderControl\Api\OrderInterface;

class Order implements OrderInterface
{
    private const DAILY_ORDER_LIMIT = 2;
    private $cartRepository;
    private $orderRepository;
    private $quoteManagement;

    private $quoteFactory;
    private $searchCriteriaBuilder;
    private $timezone;

    public function __construct(
        CartRepositoryInterface $cartRepository,
        OrderRepository $orderRepository,
        QuoteManagement $quoteManagement,
        QuoteFactory $quoteFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        TimezoneInterface $timezone
    ) {
        $this->cartRepository = $cartRepository;
        $this->orderRepository = $orderRepository;
        $this->quoteManagement = $quoteManagement;
        $this->quoteFactory = $quoteFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->timezone = $timezone;
    }

    /**
     * @inheritDoc
     * @throws LocalizedException
     */
    public function submitOrder(int $cartId, int $customerId): array
    {
        $quoteData = $this->cartRepository->get($cartId);

        // Convert CartInterface to Quote Model
        $quote = $this->quoteFactory->create();
        $quote->load($quoteData->getId());

        if (!$quote->getId()) {
            throw new NoSuchEntityException(__('Cart not found.'));
        }

        if(!$quote->hasItems()) {
            throw new LocalizedException(__('Cart is empty.'));
        }

        if ($quote->getCustomerId() != $customerId) {
            throw new LocalizedException(__('Cart does not belong to this customer.'));
        }


        if($this->hasExceededDailyLimit($customerId)) {
            $resetTime = $this->getResetTime();
            return [
                'error' => true,
                'message' => __('Daily order limit exceeded. You can only place 2 orders per day.'),
                'reset_at' => $resetTime->format('Y-m-d H:i:s'),
            ];
        }

        // Set customer data
        $quote->setCustomerId($customerId);

        $quote->collectTotals();

        // Submit the order
        $order = $this->quoteManagement->submit($quote);

        if($order) {
            return [
                'error' => false,
                'message' => __('Order placed successfully.'),
                'order_id' => $order->getIncrementId(),
                'order_date' => $this->timezone->date()->format('Y-m-d H:i:s'),
            ];
        }

        throw new LocalizedException(__('Could not place order.'));
    }

    private function hasExceededDailyLimit($customerId): bool
    {
        $today = $this->timezone->date()->setTime(0, 0, 0);
        $tomorrow = $this->timezone->date()->setTime(0, 0, 0)->modify('+1 day');

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('customer_id', $customerId)
            ->addFilter('created_at', $today->format('Y-m-d H:i:s'), 'gteq')
            ->addFilter('created_at', $tomorrow->format('Y-m-d H:i:s'), 'lt')
            ->create();

        $orders = $this->orderRepository->getList($searchCriteria);
        return $orders->getTotalCount() >= self::DAILY_ORDER_LIMIT;
    }

    /**
     * Get reset time for daily limit (next day at 1:00 PM)
     *
     * @return \DateTime
     */
     private function getResetTime(): \DateTime
     {
         $tomorrow = $this->timezone->date()->modify('+1 day');
         return $tomorrow->setTime(13, 0, 0 );
     }
}
