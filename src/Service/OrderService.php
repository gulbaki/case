<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class OrderService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ProductRepository $productRepository,
        private CustomerRepository $customerRepository,
        private OrderRepository $orderRepository
    ) {
    }

    /**
     * Create a new Order.
     *
     * @param int   $customerId
     * @param array $items [ [ 'productId' => ..., 'quantity' => ...], ... ]
     */
    public function createOrder(int $customerId, array $items): Order
    {
        $this->em->getConnection()->beginTransaction(); // Start transaction

        try {
            $customer = $this->customerRepository->find($customerId);
            if (!$customer) {
                throw new BadRequestException("Invalid customer ID: $customerId");
            }

            $order = new Order();
            $order->setCustomer($customer);

            $orderTotal = 0.0;

            foreach ($items as $itemData) {
                $productId = $itemData['productId'];
                $quantity = $itemData['quantity'];

                /** @var Product $product */
                $product = $this->productRepository->find($productId);
                if (!$product) {
                    throw new BadRequestException("Product not found with ID: $productId");
                }

                // Check stock
                if ($product->getStock() < $quantity) {
                    throw new BadRequestException("Insufficient stock for product ID: $productId");
                }

                // Decrease stock
                $product->setStock($product->getStock() - $quantity);

                $unitPrice = (float) $product->getPrice();
                $totalItem = $unitPrice * $quantity;

                $orderItem = new OrderItem();
                $orderItem->setOrder($order);
                $orderItem->setProduct($product);
                $orderItem->setQuantity($quantity);
                $orderItem->setUnitPrice($unitPrice);
                $orderItem->setTotal($totalItem);

                $orderTotal += $totalItem;

                $this->em->persist($orderItem);
            }

            $order->setTotal($orderTotal);

            $this->em->persist($order);
            $this->em->flush();

            $this->em->getConnection()->commit(); // Commit transaction


            return $order;
        } catch (BadRequestException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->em->getConnection()->rollBack(); // Rollback for all other exceptions
            throw new \RuntimeException("Failed to create order: " . $e->getMessage());
        }

    }

    public function listOrders(): array
    {
        return $this->orderRepository->findAll();
    }

    public function deleteOrder(int $orderId): void
    {
        $this->em->getConnection()->beginTransaction();
        
        try {
            $order = $this->orderRepository->find($orderId);
            if (!$order) {
                throw new BadRequestException("Order not found with ID: $orderId");
            }

            $this->em->remove($order);
            $this->em->flush();

        } catch (BadRequestException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->em->getConnection()->rollBack();
            throw new \RuntimeException("Failed to delete order: " . $e->getMessage());
        }
    }
}
