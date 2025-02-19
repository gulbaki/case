<?php

namespace App\Service\Discount;

use App\Entity\Order;

interface DiscountRuleInterface
{
    /**
     * @return bool Returns true if this rule applies to the given order.
     */
    public function isApplicable(Order $order): bool;

    /**
     * Applies the discount to the given order and returns an associative array:
     * [
     *   'discountReason' => string,
     *   'discountAmount' => float,
     *   'newSubtotal' => float
     * ]
     */
    public function apply(Order $order, float $currentSubtotal): ?array;

    /**
     * @return int Priority of this rule (lower = higher priority or vice versa).
     */
    public function getPriority(): int;
}
