<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Facades\Invoice;

class InvoicesController extends Controller
{
    public function salesInvoice($id)
    {

        $saleOrder = SalesOrder::with(['user', 'productSalesOrders.product', 'customer'])->findOrFail($id);

        if (isset($saleOrder->customer)) {

            if (!$saleOrder->customer->name) {
                $saleOrder->customer->name = 'Name not provided';
            }
            $customer = new Buyer([

                'name'          => $saleOrder->customer->name,
                'custom_fields' => [
                    'phone'         => $saleOrder->customer->phone,
                ]
            ]);
        } else {
            $customer = new Buyer([

                'name'          => 'Name not provided',
                'custom_fields' => [
                    'phone'         => 'Phone not provided',
                ]
            ]);
        }


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

    public function purchaseInvoice($id)
    {

        $purchaseOrder = PurchaseOrder::with(['user', 'supplier', 'productPurchaseOrders.product'])->findOrFail($id);

        $buyer = new Party([
            'name' => 'Inventive', // Fetch dynamically if needed
            'address' => 'ITI',
            'phone' => '+1 (555) 123-4567', // Your company's phone number
            'custom_fields' => [
                'VAT ID' => 'VAT123456789', // Example VAT or tax number
            ],
        ]);

        $supplierData = $purchaseOrder['supplier'];
        $supplier = new Party([
            'name' => $supplierData['name'],
            'address' => $supplierData['address'],
            'phone' => $supplierData['phone'],
        ]);

        $items = [];
        foreach ($purchaseOrder['productPurchaseOrders'] as $productOrder) {
            $product = $productOrder['product'];
            $items[] = (new InvoiceItem())
                ->title($product['name'])
                ->quantity($productOrder['quantity'])
                ->pricePerUnit($productOrder['price']);
        }

        $invoice = Invoice::make('PO-' . $purchaseOrder['id']) // Dynamic invoice number
            ->buyer($buyer)
            ->seller($supplier)
            ->date(now())
            ->dateFormat('Y-m-d')
            ->currencySymbol('$') // Currency symbol (can be dynamic)
            ->currencyCode('USD') // Currency code (can be dynamic)
            ->currencyThousandsSeparator(',')
            ->currencyDecimalPoint('.')
            ->addItems($items);
        // ->logo('https://cdn4.iconfinder.com/data/icons/social-media-and-logos-11/32/Logo_dropbox_box-1024.png'); // Your company logo URL

        // Download the invoice
        return $invoice->stream();
    }
}
