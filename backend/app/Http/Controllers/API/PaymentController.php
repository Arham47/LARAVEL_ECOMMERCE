<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MakePaymentRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function makePayment(MakePaymentRequest $request)
    {
        $products = Product::whereIn("id", $request->product_ids)->get();
        $lineItems = [];
        foreach ($products as $product) {
            $priceInCent = $product->price * 100;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $priceInCent,
                    'product_data' => [
                        'name' => $product->name,
                        'description' => $product->description,
                    ],
                ],
                'quantity' => 1,
            ];
        }

        Stripe::setApiKey(config('stripe.secret_key'));
        $session = Session::create([
            'payment_method_types' => ["card"], // Corrected to an array
            "line_items" => $lineItems,
            "mode" => "payment",
            "success_url" => "http://localhost:3000/payment/success",
            "cancel_url" => "http://localhost:3000/payment/cancel"
        ]);
        
        // Corrected response structure to directly return the URL
        return response()->json(['url' => $session->url], 200);
    }
}
