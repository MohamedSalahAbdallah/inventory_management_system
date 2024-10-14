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

            $filtered = $product->productSalesOrders->filter(function ($item) use ($days) {
                if ($days == 0) {
                    return $item->created_at->format('Y-m-d') == now()->format('Y-m-d');
                }
                return $item->created_at->diffInDays(now()) <= $days;
            });

            $quantity = $filtered->sum('quantity');

            return [
                'name' => $product->name,
                'value' => $quantity
            ];
        });

        $products = $products->sortByDesc(callback: 'value')->values();
        $names = $products->pluck('name')->slice(0, $length);
        $values = $products->pluck('value')->slice(0, $length);



        return [$names, $values];
    }

    public function SalesPerDays($length, $days)
    {
        $salesOrders = SalesOrder::get()->filter(function (SalesOrder $order) use ($days) {
            if ($days != "all") {
                if ($days == 0) {
                    return $order->created_at->format('Y-m-d') == now()->format('Y-m-d');
                }
                return $order->created_at->diffInDays(now()) <= $days;
            }
            return true;
        });


        $dates = $salesOrders->pluck('created_at')->unique()->map(function ($date) {
            return $date->format('Y-m-d');
        })->values();

        $total_amounts = $salesOrders->groupBy('created_at')->map(function ($group) {
            return $group->sum('total_amount');
        })->values();

        return [$dates, $total_amounts];
    }
    // public function PurchasesPerDays($length, $days)
    // {
    //     $purchaseOrders = PurchaseOrder::get();
    //     $purchaseOrders->transform(function (PurchaseOrder $order) use ($days) {
    //         if ($order->created_at->diffInDays(now()) <= $days) {
    //             return $order;
    //         }
    //     });
    //     $purchaseOrders = $purchaseOrders->sortByDesc(callback: 'total_amount')->values();
    //     $purchaseOrders = $purchaseOrders->slice(0, $length);
    //     return $purchaseOrders;
    // }
}
