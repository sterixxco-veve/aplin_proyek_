<?php

namespace App\Http\Controllers\Api;

use App\Models\ExpenseReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseReportResource;
use App\Http\Requests\StoreExpenseRequest;

class ExpenseReportController extends Controller
{
    public function index()
    {
        return ExpenseReportResource::collection(
            ExpenseReport::with('user')->get()
        );
    }

    public function store(StoreExpenseRequest $request)
{
    $data = ExpenseReport::create($request->validated());

    return new ExpenseReportResource($data);
}

    public function update(Request $request, $id)
    {
        $data = ExpenseReport::findOrFail($id);
        $data->update($request->all());

        return new ExpenseReportResource($data);
    }

    public function destroy($id)
    {
        ExpenseReport::destroy($id);

        return response()->json(['message' => 'Deleted']);
    }
}