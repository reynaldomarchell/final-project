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
        
        $newDonation = Donation::create([
            'user_id' => $user->id,
            'event_id' => $validate['event_id'],
            'amount' => $validate['amount'],
            'date' => now()
        ]);
    
        return response()->json([
            "message" => "successfully created new donation",
            "donation" => $newDonation
        ]);
    }
}
