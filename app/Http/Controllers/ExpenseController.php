<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use Illuminate\Http\Response;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Expense::all()->toJson();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'description' => 'required|string',
            'amount' => 'required|numeric'
        ]);
        $expense = Expense::create($data);
        return response($expense, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return response()->json(['message' => 'Image not found'], Response::HTTP_NOT_FOUND);
        }
        return response($expense, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'amount' => 'numeric',
            'description' => 'string'
        ]);

        // returns 404 automatically if find fails sogbo Dami
        $expense = Expense::findOrFail($id);

        $expense->update($validatedData);

        return response()->json(['message' => 'Expense updated successfully', 'expense' => $expense]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        return Expense::destroy($id);
    }
}