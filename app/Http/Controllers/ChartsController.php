<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use Illuminate\Http\Request;

class ChartsController extends Controller
{
    public function topSellingProducts($length, $days)
    {

        $products = Product::with('productSalesOrders')->get();
        $products->transform(function (Product $product) use ($days) {

            $quantity = 0;
            foreach ($product->productSalesOrders as $item) {
                if ($item->created_at->diffInDays(now()) <= $days) {
                    $quantity += $item->quantity;
                }
            }

            return [
                'name' => $product->name,
                'value' => $quantity
            ];
            // return $product;
        });

        $products = $products->sortByDesc(callback: 'value')->values();
        $products = $products->slice(0, $length);



        return $products;
    }

    public function SalesPerDays($length, $days)
    {
        $salesOrders = SalesOrder::get();
        $salesOrders->transform(function (SalesOrder $order) use ($days) {
            if ($order->created_at->diffInDays(now()) <= $days) {
                return $order;
            }
        });
        $salesOrders = $salesOrders->sortByDesc(callback: 'total_amount')->values();
        $salesOrders = $salesOrders->slice(0, $length);
        return $salesOrders;
    }

    public function PurchasesPerDays($length, $days)
    {
        $purchaseOrders = PurchaseOrder::get();
        $purchaseOrders->transform(function (PurchaseOrder $order) use ($days) {
            if ($order->created_at->diffInDays(now()) <= $days) {
                return $order;
            }
        });
        $purchaseOrders = $purchaseOrders->sortByDesc(callback: 'total_amount')->values();
        $purchaseOrders = $purchaseOrders->slice(0, $length);
        return $purchaseOrders;
    }
}
