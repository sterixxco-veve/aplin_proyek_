<?php

namespace App\Http\Controllers\Api;

use App\Models\EventCommittee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventCommitteeResource;

class EventCommitteeController extends Controller
{
    public function index()
    {
        return EventCommitteeResource::collection(
            EventCommittee::with(['user', 'division'])->get()
        );
    }

    public function store(Request $request)
    {
        $data = EventCommittee::create($request->all());

        return new EventCommitteeResource($data);
    }

    public function show($id)
    {
        return new EventCommitteeResource(
            EventCommittee::with(['user', 'division'])->findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $data = EventCommittee::findOrFail($id);
        $data->update($request->all());

        return new EventCommitteeResource($data);
    }

    public function destroy($id)
    {
        EventCommittee::destroy($id);

        return response()->json(['message' => 'Deleted']);
    }
}