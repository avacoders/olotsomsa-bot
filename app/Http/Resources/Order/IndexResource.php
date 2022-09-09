<?php

namespace App\Http\Resources\Order;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $sum = 0;
        $quantity = 0;
        $text = [];

        foreach ($this->order_products as $order_product) {
            $sum += $order_product->product->price * $order_product->quantity;
            $quantity += $order_product->quantity;
            $text[] = $order_product->product->name;
        }
        $sum += $this->posuda;
        $sum += $this->delivery_price;

        return [
            'id' => $this->id,
            'text' => implode(', ', $text),
            'sum' => $sum,
            'quantity' => $quantity,
            'time' => Carbon::parse($this->updated_at)->format('H:i'),
            'date' => Carbon::parse($this->updated_at)->format('d.m.Y'),
        ];
    }
}
