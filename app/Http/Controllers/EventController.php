<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $events = Event::paginate(10);
        return response()->json($events,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|string',
            'date' => 'required|date'
        ]);

        $newEvent = Event::create([
            'title' => $validate['title'],
            'description' => $validate['description'],
            'date' => $validate['date'],
            'image' => $validate['image'],
            'donationTotal' => 0
        ]);

        return response()->json($newEvent,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::with([
            'donation' => function ($query) {
                $query->select('id', 'event_id', 'user_id', 'amount', 'date');
            },
            'donation.user' => function ($query) {
                $query->select('id', 'name');
            }
        ])->findOrFail($id);
        return response()->json($event,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);
        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|string',
            'date' => 'required|date'
        ]);

        $event->title = $validate['title'];
        $event->description = $validate['description'];
        $event->image = $validate['image'];
        $event->date = $validate['date'];

        $event->save();

        return response()->json($event,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json(["message"=>"successfully deleted event"],200);
    }
}
