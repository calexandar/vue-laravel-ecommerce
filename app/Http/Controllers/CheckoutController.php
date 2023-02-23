<?php

namespace App\Http\Controllers;


use App\Helpers\Cart;
use Stripe\StripeClient;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $stripe = new StripeClient(getenv('STRIPE_SECRET_KEY'));

        [$products, $cartItems] = Cart::getProductsAndCartItems();

        $line_items = [];
        foreach ($products as $product) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                      'name' => $product->title,
                      'images' => [$product->image]
                    ],
                    'unit_amount' => $product->price * 100,
                  ],
                  'quantity' => $cartItems[$product->id]['quantity'],
            ];
        }

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => 'http://localhost:8000/success',
            'cancel_url' => 'http://localhost:8000/cancel',
          ]);
          
        //   header("HTTP/1.1 303 See Other");
        //   header("Location: " . $checkout_session->url);

        return redirect($checkout_session->url);
    }
}
