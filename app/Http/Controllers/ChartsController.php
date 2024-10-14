<?php

namespace App\Http\Controllers;

use App\Models\Category;
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
                if ($days != 'all') {
                    # code...
                    if ($days == 0) {
                        return $item->created_at->format('Y-m-d') == now()->format('Y-m-d');
                    }
                    return $item->created_at->diffInDays(now()) <= $days;
                }

                return true;
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
    public function SalesPerDays($days)
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
        })->unique()->values();

        $total_amounts = $salesOrders->groupBy('created_at')->map(function ($group) {
            return $group->sum('total_amount');
        })->values();

        return [$dates, $total_amounts];
    }

    public function widgets()
    {
        $products = Product::where('quantity', '>', '0')->count();
        $sales = SalesOrder::count();
        $profit = SalesOrder::sum('total_amount');
        $categories = Category::count();

        return [
            'products' => [
                'name' => 'products count',
                'value' => $products,
            ],
            'sales' => [
                'name' => 'sales count',
                'value' => $sales,
            ],
            'profit' => [
                'name' => 'sales amount',
                'value' => $profit,
            ],
            'categories' => [
                'name' => 'categories count',
                'value' => $categories,
            ],
        ];
    }

    public function productsPerCategory($length)
    {
        $category = Category::withCount('products')->get();
        $category = $category->sortByDesc('products_count');


        $names = $category->pluck('name')->slice(0, $length);
        $values = $category->pluck('products_count')->slice(0, $length);
        return [$names, $values];
        return $category;
    }
}
