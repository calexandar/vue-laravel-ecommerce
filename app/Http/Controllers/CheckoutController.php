<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Helpers\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Payment;
use Stripe\StripeClient;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $stripe = new StripeClient(getenv('STRIPE_SECRET_KEY'));

        [$products, $cartItems] = Cart::getProductsAndCartItems();

        $line_items = [];
        $totalPrice = 0;
        foreach ($products as $product) {
          $quantity = $cartItems[$product->id]['quantity'];
          $totalPrice += $product->price * $quantity;
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
          
          $orderData = [
            'total_price' => $totalPrice,
            'status' => OrderStatus::Unpaid,
            'created_by' => $user->id,
            'updated_by' => $user->id,
          ];

          $order = Order::create($orderData);

     

          $paymentData = [
            'order_id'=> $order->id,
            'amount' => $totalPrice,
            'status' => PaymentStatus::Pending,
            'type' => 'cc',
            'created_by'=> $user->id,
            'updated_by'=> $user->id,
            'session_id' => $checkout_session->id
          ];

          Payment::create($paymentData);

          CartItem::where(['user_id' => $user->id])->delete();

        return redirect($checkout_session->url);
    }

    public function success(Request $request  )
    {
      $stripe = new StripeClient(getenv('STRIPE_SECRET_KEY'));

      try {        
        $session_id = $request->get('session_id');
        $session = $stripe->checkout->sessions->retrieve($_GET['session_id']);


        if(!$session){
          return view('checkout.failure', ['message'=> 'Session ID not valid!']);
        }

        $payment = Payment::query()
                              ->where([
                                  'session_id' => $session_id,
                                  'status' => PaymentStatus::Pending
                              ])
                              ->first();                     

        if(!$payment || $payment->status !== PaymentStatus::Pending->value){
          return view('checkout.failure', ['message'=> 'Payment not successeful!']);
        }

        $payment->status = PaymentStatus::Paid;
        $payment->update();

        $order = $payment->order;
        $order->status = OrderStatus::Paid;
        $order->update();

        return view('checkout.success');

      } catch (\Exception $e) {
        
        return view('checkout.failure',['message'=> $e->getMessage()]);
      }
 


     
    }

    public function failure(Request $request)
    {
      
      dd($request->all());
    }
}
