<?php

namespace App\Http\Controllers;

use App\Actions\Order\CreateOrderAction;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request, CreateOrderAction $createOrderAction): mixed
    {
        // Validate the request
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            $order = $createOrderAction->execute($validatedData);

            DB::commit();

            $order->load(relations: 'products');

            return response()->json([
                'message' => 'Order placed successfully',
                'order' => new OrderResource($order),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Order failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }
}
