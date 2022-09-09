<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Geolocation;
use App\Helpers\Telegram;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrderRequest;
use App\Models\Location;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public $telegram;

    public function __construct()
    {
        $this->telegram = new Telegram(config('bots.bot'));
    }

    public function store(OrderRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $orders = $data['orders'];
            unset($data['orders']);
            $data['user_id'] = auth()->user()->id;
            $data['status_id'] = Order::STATUS_RECEIVED;
            $data['type'] = $data['order_type'];
            unset($data['order_type']);
            $long = $request->long;
            $lat = $request->lat;
            unset($data['lat']);
            unset($data['long']);
            $order = Order::create($data);
            foreach ($orders as $item) {
                if (isset($item['quantity']) && $item['quantity']) {
                    $order_product = [
                        'product_id' => $item['id'],
                        'order_id' => $order->id,
                        'status_id' => OrderProduct::STATUS_BASKET,
                        'quantity' => $item['quantity'],
                    ];
                    OrderProduct::create($order_product);
                }
            }
            if ($long && $lat) {
                $location = [
                    "longitude" => $long,
                    "latitude" => $lat,
                ];
                $geolocation = new Geolocation();

                $address = $geolocation->Get_Address_From_Google_Maps($location['latitude'], $location['longitude']);
                $location1 = Location::create([
                    'user_id' => 1,
                    'longitude' => $location['longitude'],
                    'latitude' => $location['latitude'],
                    'text' => $address,
                ]);

                $l['location_id'] = (int)$location1->id;
                $order->update($l);
            }
            $text = $this->telegram->makeTextFromSite($order);
            $buttons = $this->telegram->buttonsFromSite($order);
            $this->telegram->sendMessageWithButtons(env("TELEGRAM_ORDER_ID"), $text, json_encode($buttons));
            DB::commit();
            return response()->json(['ok' => true, "message" => "Muvaqqiyatli qabul qilindi! Sizga operatorimiz"]);
        } catch
        (\Exception $exception) {
            DB::rollBack();
            return response()->json(['ok' => false, "message" => $exception->getMessage()], 500);

        }
    }


}
