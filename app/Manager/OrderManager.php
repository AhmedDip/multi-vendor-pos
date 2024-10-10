<?php

namespace App\Manager;

use App\Models\Product;
use Illuminate\Http\Request;


class OrderManager
{
    public function calculateItems($items)
    {
        $totalAmount = 0;
        // $discountAmount = 0;
        $calculatedItems = [];
    
        foreach ($items as $item) {
            $product = Product::query()->where('id', $item['product_id'])->first();
            if (!$product) {
                throw new \Exception('Product not found');
            }
    
            // $unitPrice = isset($item['unit_price']) ? $item['unit_price'] : ($product->price - $product->discount_price);
            $unitPrice = isset($item['unit_price']) ? $item['unit_price'] : $product->price;
            $totalPrice = isset($item['total_price']) ? $item['total_price'] : $unitPrice * $item['quantity'];
    
            $totalAmount += $totalPrice;
            // $discountPrice = $product->discount_price * $item['quantity'];
            // $discountAmount += $discountPrice;

            
    
            $calculatedItem = [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
            ];
    
            if (isset($item['assign_to'])) {
                $calculatedItem['assign_to'] = $item['assign_to'];
            }
    
            $calculatedItems[] = $calculatedItem;
        }
    
        return [
            'items' => $calculatedItems,
            'total_amount' => $totalAmount,
            // 'discount_amount' => $discountAmount,
        ];
    }
}

