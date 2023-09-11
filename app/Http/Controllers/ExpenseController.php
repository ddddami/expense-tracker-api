<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Expense;


class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return auth()->user()->expenses(); doesn't work sha
        $userId = auth()->user()->id;
        return response(Expense::where('user_id', $userId)->get());
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
    public function show(Request $request, int $id)
    {
        try {
            $expense = Expense::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Expense not found'], 404);
        }
        if ($expense->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Expense does not belong to you'], 403);
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