<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderListResource;

class OrderContoller extends Controller
{
    public function index()
    {
        $perPage = request('per_page',10);
        $search = request('search', '');
        $sortField = request('sort_field', 'updated_at');
        $sortDirection = request('sort_direction', 'desc');

        $query = Order::query()
            ->where('id', 'like', "%{$search}%")
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);

            return OrderListResource::collection($query);
    }

    public function view(Order $order)
    {
         /** @var \App\Models\User $user */
         $user = \request()->user();
        
         if($order->created_by !== $user->id){
            return response("You don't have permission to view this order", 403);
         }

        return view('order.view',compact('order'));
    }
}
