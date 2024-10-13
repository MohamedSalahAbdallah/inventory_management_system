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
            if ($days != "all") {
                # code...
                foreach ($product->productSalesOrders as $item) {
                    if ($item->created_at->diffInDays(now()) <= $days) {
                        $quantity += $item->quantity;
                    }
                }
            } else {
                foreach ($product->productSalesOrders as $item) {

                    $quantity += $item->quantity;
                }
            }

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

    // public function SalesPerDays($length, $days)
    // {
    //     $salesOrders = SalesOrder::get();
    //     $salesOrders->transform(function (SalesOrder $order) use ($days) {
    //         if ($order->created_at->diffInDays(now()) <= $days) {
    //             return $order;
    //         }
    //     });
    //     $salesOrders = $salesOrders->sortByDesc(callback: 'total_amount')->values();
    //     $salesOrders = $salesOrders->slice(0, $length);
    //     return $salesOrders;
    // }

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

    public function salesAndPurchases($length, $days)
    {
        // sales
        $salesOrders = SalesOrder::get();
        $salesOrders->transform(function (SalesOrder $order) use ($days) {
            if ($days != "all") {
                # code...
                foreach ($order as $item) {
                    if ($item->created_at->diffInDays(now()) <= $days) {
                        return $order;
                    }
                }
            } else {
                foreach ($order as $item) {
                    return $order;
                }
            }
        });
        // purchases

        $purchaseOrders = PurchaseOrder::get();
        $purchaseOrders->transform(function (PurchaseOrder $order) use ($days) {
            if ($days != "all") {
                # code...
                foreach ($order as $item) {
                    if ($item->created_at->diffInDays(now()) <= $days) {
                        return $order;
                    }
                }
            } else {
                foreach ($order as $item) {
                    return $order;
                }
            }
        });

        // slicing
        $salesOrders = $salesOrders->sortByDesc(callback: 'total_amount')->values();
        $salesOrders = $salesOrders->slice(0, $length);

        $purchaseOrders = $purchaseOrders->sortByDesc(callback: 'total_amount')->values();
        $purchaseOrders = $purchaseOrders->slice(0, $length);

        $dates = $salesOrders->pluck('created_at')->merge($purchaseOrders->pluck('created_at'))->sort()->slice(0, $length)->unique();

        $sales_totals = [];
        $purchases_totals = [];
        foreach ($dates as $date) {
            $sales_totals[] = $salesOrders->where('created_at', $date)->sum('total_amount') ?: 0;
            $purchases_totals[] = $purchaseOrders->where('created_at', $date)->sum('total_amount') ?: 0;
        }

        return [["sales", "purchases"], [
            $sales_totals,
            $purchases_totals
        ], $dates];
    }
}
