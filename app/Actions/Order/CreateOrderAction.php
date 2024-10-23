<?php

namespace App\Actions\Order;

use App\Models\Order;
use App\Models\Product;

class CreateOrderAction
{
    public function execute(array $data): Order
    {
        // Calculate total price
        $totalPrice = 0;
        $products = [];

        foreach ($data['products'] as $productData) {
            $product = Product::find($productData['id']);

            // Ensure stock is sufficient
            if ($product->stock < $productData['quantity']) {
                throw new \Exception("Insufficient stock for product: " . $product->name);
            }

            // Deduct stock
            $product->stock -= $productData['quantity'];
            $product->save();

            // Add to the order total
            $totalPrice += $product->price * $productData['quantity'];

            // Prepare product data for attaching to the order
            $products[$product->id] = ['quantity' => $productData['quantity']];
        }

        // Create the order
        $order = Order::create([
            'user_id' => auth()->user()->id,
            'total_price' => $totalPrice,
        ]);

        // Attach products to the order
        $order->products()->attach($products);

        return $order;
    }
}
