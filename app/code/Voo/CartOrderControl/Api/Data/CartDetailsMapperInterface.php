<?php

namespace Voo\CartOrderControl\Api\Data;

use Magento\Quote\Api\Data\TotalsInterface;
use Magento\Quote\Api\Data\CartInterface as QuoteInterface;
use Magento\Quote\Model\Quote;


interface CartDetailsMapperInterface
{
    /**
     * Map quote totals to cart details
     *
     * @param Quote $quoteTotals
     * @return CartDetailsInterface
     */
    public function map(Quote $quote): CartDetailsInterface;
}
