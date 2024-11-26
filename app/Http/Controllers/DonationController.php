<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Event;

class DonationController extends Controller
{
    public function getDonationByEventId(string $eventId){
        $donations = Donation::where("event_id",$eventId)->get();
        return response()->json($donations);
    }

    public function getDonationByUserId(Request $request){
        $user = $request->user();
        $donations = Donation::where("user_id",$user->id)->get();
        return response()->json($donations);
    }
    
    public function store(Request $request){
        $validate = $request->validate([
            'event_id' => 'required|integer',
            'amount' => 'required|integer|min:0'
        ]);

        $getEvent = Event::where("id",$validate['event_id'])->first();
        if ($getEvent == null){
            return response()->json(["message"=>"event with id ".$validate['event_id']." doesn't exist"],409);
        }

        $getEvent->donationTotal += $validate['amount'];
        $getEvent->save();
        $user = $request->user();
        
        
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;
        \Midtrans\Config::$curlOptions = [
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ];
        $params = [
            'transaction_details' => [
                'order_id' => rand(),
                'gross_amount' => $validate['amount'],
            ],
            'item_details' => [
                [
                    'id' => 'item1',
                    'price' => $validate['amount'],
                    'quantity' => 1,
                    'name' => 'Donation',
                ]
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => '90000000'
            ]
        ];
        
        $snapToken = \Midtrans\Snap::createTransaction($params)->redirect_url;

        return response()->json([
            "redirect" => $snapToken
        ]);
    }
}
