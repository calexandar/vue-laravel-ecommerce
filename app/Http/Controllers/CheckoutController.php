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
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' =>  route('checkout.failure', [], true),
          ]);
          
          // dd($checkout_session->id);

        return redirect($checkout_session->url);
    }

    public function success(Request $request  )
    {
      $stripe = new StripeClient(getenv('STRIPE_SECRET_KEY'));

      try {
        $session = $stripe->checkout->sessions->retrieve($_GET['session_id']);
        if(!$session){
          return view('checkout.failure');
        }
        return view('checkout.success');

      } catch (\Exception $e) {
        
        return view('checkout.failure');
      }
 


     
    }

    public function failure(Request $request)
    {
      
      dd($request->all());
    }
}
