<?php

namespace App\Service\Discount\Rule;

use App\Entity\Order;
use App\Entity\OrderItem;

use App\Service\Discount\AbstractDiscountRule;


class TwoOrMoreCategoryDiscountRule extends AbstractDiscountRule
{
    protected int $priority = 30;

    private int $category;
    private float $discountRate;

    public function __construct(int $category, float $discountRate = 0.20)
    {
        $this->category = $category;
        $this->discountRate = $discountRate;
    }

    public function isApplicable(Order $order): bool
    {
        $count = 0;
        foreach ($order->getItems() as $item) {
            if ($item->getProduct()->getCategory() === $this->category) {
                $count++;
            }
            if ($count >= 2) {
                return true;
            }
        }
        return false;
    }

    public function apply(Order $order, float $currentSubtotal): ?array
    {
        // Identify the cheapest item in the relevant category
        $categoryItems = [];
        foreach ($order->getItems() as $item) {
            if ($item->getProduct()->getCategory() === $this->category) {
                $categoryItems[] = $item;
            }
        }

        if (count($categoryItems) < 2) {
            return null;
        }

        usort($categoryItems, function (OrderItem $a, OrderItem $b) {
            return ($a->getUnitPrice() <=> $b->getUnitPrice());
        });

        // Cheapest item
        $cheapestItem = $categoryItems[0];
        $discountAmount = $cheapestItem->getUnitPrice() * $this->discountRate;
        $newSubtotal = $currentSubtotal - $discountAmount;

        return [
            'discountReason' => 'CHEAPEST_IN_CATEGORY_' . $this->category,
            'discountAmount' => round($discountAmount, 2),
            'subtotal' => round($newSubtotal, 2),
        ];
    }
}
