<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ChartsController extends Controller
{
    public function topSellingProducts($length)
    {

        $products = Product::with('productSalesOrders')->get();
        $products->transform(function ($product) use (&$quantity) {

            $quantity = 0;
            foreach ($product->productSalesOrders as $item) {
                $quantity += $item->quantity;
            }
            $product['total_sales'] = $quantity;
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
}
