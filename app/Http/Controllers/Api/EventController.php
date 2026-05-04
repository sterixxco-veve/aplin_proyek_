<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;

class EventController extends Controller
{
    public function index()
    {
        return EventResource::collection(
            Event::with(['organization'])->latest()->get()
        );
    }

    public function store(StoreEventRequest $request)
{
    $event = Event::create($request->validated());
    return new EventResource($event);
}

    public function show($id)
    {
        $event = Event::with([
            'organization',
            'committees.user',
            'committees.division',
            'tasks.assignee'
        ])->findOrFail($id);

        return new EventResource($event);
    }

    
public function update(UpdateEventRequest $request, $id)
{
    $event = Event::findOrFail($id);
    $event->update($request->validated());

    return new EventResource($event);
}

    public function destroy($id)
    {
        Event::destroy($id);

        return response()->json(['message' => 'Deleted']);
    }
}