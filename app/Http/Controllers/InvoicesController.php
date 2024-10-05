<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Facades\Invoice;

class InvoicesController extends Controller
{
    public function salesInvoice($id)
    {

        $saleOrder = SalesOrder::with(['user', 'productSalesOrders.product'])->findOrFail($id);

        $customer = new Buyer([
            'name'          => $saleOrder->user->name,
            'custom_fields' => [
                'email'         => $saleOrder->user->email,
                'address'       => $saleOrder->user->address,
                'phone'         => $saleOrder->user->phone,
            ]
        ]);

        $items = [];

        foreach ($saleOrder['productSalesOrders'] as $productOrder) {
            $item = (new InvoiceItem())
                ->title($productOrder['product']['name'])
                ->pricePerUnit($productOrder['price'])
                ->quantity($productOrder['quantity'])
                ->discount(0)
                ->subTotalPrice($productOrder['price'] * $productOrder['quantity']);

            $items[] = $item;
        }

        // Create the invoice
        $invoice = Invoice::make()
            ->buyer($customer)
            ->date(now())
            ->dateFormat('m/d/Y')
            ->sequence($saleOrder['id'])
            ->addItems($items)
            ->totalAmount($saleOrder['total_amount'])
            ->currencySymbol('$')
            ->currencyCode('USD')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator(',')
            ->currencyDecimalPoint('.');

        // Download the invoice
        return $invoice->stream();
    }
}
