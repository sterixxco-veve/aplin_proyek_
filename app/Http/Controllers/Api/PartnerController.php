<?php

namespace App\Http\Controllers\Api;

use App\Models\Partner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PartnerResource;

class PartnerController extends Controller
{
    public function index()
    {
        return PartnerResource::collection(
            Partner::with('pic')->get()
        );
    }

    public function store(Request $request)
    {
        $data = Partner::create($request->all());

        return new PartnerResource($data);
    }

    public function update(Request $request, $id)
    {
        $data = Partner::findOrFail($id);
        $data->update($request->all());

        return new PartnerResource($data);
    }

    public function destroy($id)
    {
        Partner::destroy($id);

        return response()->json(['message' => 'Deleted']);
    }
}