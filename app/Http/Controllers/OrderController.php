<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;

class OrderController extends Controller
{

    /**
     * @OA\Post(
     *      path="/create-order",
     *      operationId="createOrder",
     *      summary="Create order",
     *      description="Create order then return order_id",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       )
     *     )
     */
    public function createOrder(Request $request)
    {
        $order = Order::create([
            'customer_name' => $request->input('customer_name'),
            'customer_email' => $request->input('customer_email'),
            'receipt_type' => $request->input('receipt_type'),
            'status' => 'új'
        ]);
        
        $products = $request->input('products');

        foreach($products as $product){
            OrderItem::create([
                'product_name' => $product["product_name"],
                'quantity' => $product["quantity"],
                'price' => $product["price"],
                'order_id' => $order->id
            ]);
        }
        
        Address::create([
            'delivery_name' => $request->input('delivery_name'),
            'delivery_zip_code' => $request->input('delivery_zip_code'),
            'delivery_city' => $request->input('delivery_city'),
            'delivery_street' => $request->input('delivery_street'),
            'billing_name' => $request->input('billing_name') ?? $request->input('delivery_name'),
            'billing_zip_code' => $request->input('billing_zip_code') ?? $request->input('delivery_zip_code'),
            'billing_city' => $request->input('billing_city') ?? $request->input('delivery_city'),
            'billing_street' => $request->input('billing_street') ?? $request->input('delivery_street'),
            'order_id' => $order->id
        ]);

        return response()->json($order->id, 200);
    }

    /**
     * @OA\Post(
     *      path="/list-orders",
     *      operationId="listOrders",
     *      summary="List orders",
     *      description="List orders with or without filters",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       )
     *     )
     */
    public function listOrders(Request $request)
    {
        $order_id = $request->input('order_id') ?? null;
        $status = $request->input('status') ?? null;
        
        $result = [];

        if($order_id === null && $status === null) {
            $orders = Order::all();
            foreach($orders as $order) {
                $result[] = [
                    "order_id" => $order->id,
                    "status" => $order->status,
                    "customer_name" => $order->customer_name,
                    "created_at" => $order->created_at        
                ];
            }
            
            return response()->json($result, 200);
        }

        $query = DB::table('orders');
        if($order_id !== null) {
            $query->where('id', $order_id);
        }
        if($status !== null) {
            $query->where('status', $status);
        }
        $orders = $query->get();

        foreach($orders as $order) {
            $result[] = [
                "order_id" => $order->id,
                "status" => $order->status,
                "customer_name" => $order->customer_name,
                "created_at" => $order->created_at        
            ];
        }

        return response()->json($result, 200);
    }

    /**
     * @OA\Post(
     *      path="/change-status",
     *      operationId="changeStatus",
     *      summary="Change order status",
     *      description="Change order status",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent()
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       )
     *     )
     */
    public function changeStatus(Request $request)
    {
        $order_id = $request->input('order_id');
        $status = $request->input('status');

        $order = Order::findOrFail($order_id);
        $order->update(["status" => $status]);

        return response()->json($order->id, 200);
    }
}
