<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Service\Discount\DiscountCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/discounts')]
class DiscountController extends AbstractController
{
    public function __construct(
        private DiscountCalculator $discountCalculator,
        private OrderRepository $orderRepository
    ) {
    }

    #[Route('/{orderId}', name: 'discount_calculate', methods: ['GET'])]
    public function calculate(int $orderId): JsonResponse
    {
        $order = $this->orderRepository->find($orderId);
        if (!$order) {
            return $this->json(['error' => 'Order not found'], 404);
        }

        $result = $this->discountCalculator->calculate($order);

        return $this->json($result);
    }
}
