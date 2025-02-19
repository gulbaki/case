<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\DiscountRule;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $customers = [];
        $customerData = [
            ['id' => 1, 'name' => 'Türker Jöntürk', 'since' => '2014-06-28', 'revenue' => '492.12'],
            ['id' => 2, 'name' => 'Kaptan Devopuz', 'since' => '2015-01-15', 'revenue' => '1505.95'],
            ['id' => 3, 'name' => 'İsa Sonuyumaz', 'since' => '2016-02-11', 'revenue' => '0.00']
        ];

        foreach ($customerData as $data) {
            $customer = new Customer();
            $customer->setName($data['name']);
            $customer->setSince(new \DateTime($data['since']));
            $customer->setRevenue($data['revenue']);
            $manager->persist($customer);
            $customers[$data['id']] = $customer;
        }

        $products = [];
        $productData = [
            ['id' => 100, 'name' => 'Black&Decker A7062 40 Parça Tornavida Seti', 'category' => 1, 'price' => '120.75', 'stock' => 10],
            ['id' => 101, 'name' => 'Reko Mini Tamir Hassas Tornavida Seti 32\'li', 'category' => 1, 'price' => '49.50', 'stock' => 10],
            ['id' => 102, 'name' => 'Viko Karre Anahtar - Beyaz', 'category' => 2, 'price' => '11.28', 'stock' => 10],
            ['id' => 103, 'name' => 'Legrand Salbei Anahtar, Alüminyum', 'category' => 2, 'price' => '22.80', 'stock' => 10],
            ['id' => 104, 'name' => 'Schneider Asfora Beyaz Komütatör', 'category' => 2, 'price' => '12.95', 'stock' => 10]
        ];

        foreach ($productData as $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setCategory($data['category']);
            $product->setPrice($data['price']);
            $product->setStock($data['stock']);
            $manager->persist($product);
            $products[$data['id']] = $product;
        }

        $orderData = [
            ['id' => 1, 'customerId' => 1, 'items' => [
                ['productId' => 102, 'quantity' => 10, 'unitPrice' => '11.28', 'total' => '112.80']
            ], 'total' => '112.80'],
            ['id' => 2, 'customerId' => 2, 'items' => [
                ['productId' => 101, 'quantity' => 2, 'unitPrice' => '49.50', 'total' => '99.00'],
                ['productId' => 100, 'quantity' => 1, 'unitPrice' => '120.75', 'total' => '120.75']
            ], 'total' => '219.75'],
            ['id' => 3, 'customerId' => 3, 'items' => [
                ['productId' => 102, 'quantity' => 6, 'unitPrice' => '11.28', 'total' => '67.68'],
                ['productId' => 100, 'quantity' => 10, 'unitPrice' => '120.75', 'total' => '1207.50']
            ], 'total' => '1275.18']
        ];

        foreach ($orderData as $data) {
            $order = new Order();
            $order->setCustomer($customers[$data['customerId']]);
            $order->setTotal($data['total']);

            foreach ($data['items'] as $itemData) {
                $orderItem = new OrderItem();
                $orderItem->setOrder($order);
                $orderItem->setProduct($products[$itemData['productId']]);
                $orderItem->setQuantity($itemData['quantity']);
                $orderItem->setUnitPrice($itemData['unitPrice']);
                $orderItem->setTotal($itemData['total']);
                $manager->persist($orderItem);
            }

            $manager->persist($order);
        }

        $discountData = [
            ['ruleCode' => '10_PERCENT_OVER_1000', 'parameters' => ['threshold' => 1000, 'rate' => 0.10], 'priority' => 1, 'active' => true],
            ['ruleCode' => 'BUY_6_GET_1', 'parameters' => ['category' => 2], 'priority' => 2, 'active' => true],
            ['ruleCode' => 'CHEAPEST_IN_CATEGORY', 'parameters' => ['category' => 2, 'discountRate' => 0.20], 'priority' => 3, 'active' => true]
        ];

        foreach ($discountData as $data) {
            $discountRule = new DiscountRule();
            $discountRule->setRuleCode($data['ruleCode']);
            $discountRule->setParameters($data['parameters']);
            $discountRule->setPriority($data['priority']);
            $discountRule->setActive($data['active']);
            $manager->persist($discountRule);
        }

        $manager->flush();
    }
}
