<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ChartsController extends Controller
{
    public function topSellingProducts($length)
    {

        $products = Product::withCount('productSalesOrders')->get();
        $products = $products->sortByDesc('product_sales_orders_count')->values();
        $products = $products->slice(0, $length);
        return $products;
    }
}
