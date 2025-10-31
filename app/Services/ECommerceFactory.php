<?php

namespace App\Services;

use App\Models\Store;
use App\Services\Contracts\ECommerceServiceInterface;
use App\Services\WooCommerceService;
use App\Services\ShopifyService;
use App\Services\MagentoService;
use Exception;

class ECommerceFactory
{
    public static function make(Store $store): ECommerceServiceInterface
    {
        switch ($store->platform) {
            case 'woocommerce':
                return new WooCommerceService($store);

            case 'shopify':
                return new ShopifyService($store);

            case 'magento':
                return new MagentoService($store);

            default:
                throw new Exception("Άγνωστη πλατφόρμα: {$store->platform}");
        }
    }
}
