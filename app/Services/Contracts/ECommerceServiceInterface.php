<?php

namespace App\Services\Contracts;

interface ECommerceServiceInterface
{
    public function getProducts();
    public function getOrders();
    public function getCustomers();
    public function syncProducts();
    public function syncOrders();
}
