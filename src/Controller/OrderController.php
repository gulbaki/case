<?php

namespace App\Controller;

use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/orders')]
class OrderController extends AbstractController
{
    public function __construct(private OrderService $orderService) {}

    #[Route('', name: 'order_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $customerId = $data['customerId'] ?? null;
        $items = $data['items'] ?? [];

        $order = $this->orderService->createOrder($customerId, $items);

        return $this->json([
            'id' => $order->getId(),
            'customer' => $order->getCustomer()->getId(),
            'total' => $order->getTotal(),
            'items' => array_map(function($item) {
                return [
                    'productId' => $item->getProduct()->getId(),
                    'quantity' => $item->getQuantity(),
                    'unitPrice' => $item->getUnitPrice(),
                    'total' => $item->getTotal()
                ];
            }, $order->getItems()->toArray())
        ], 201);
    }

    #[Route('', name: 'order_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $orders = $this->orderService->listOrders();

        $result = [];
        foreach ($orders as $order) {
            $result[] = [
                'id' => $order->getId(),
                'customerId' => $order->getCustomer()->getId(),
                'total' => $order->getTotal(),
                'items' => array_map(function($item) {
                    return [
                        'productId' => $item->getProduct()->getId(),
                        'quantity' => $item->getQuantity(),
                        'unitPrice' => $item->getUnitPrice(),
                        'total' => $item->getTotal(),
                    ];
                }, $order->getItems()->toArray()),
            ];
        }

        return $this->json($result);
    }

    #[Route('/{id}', name: 'order_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $this->orderService->deleteOrder($id);

        return $this->json(['status' => 'Order deleted'], 200);
    }
}
