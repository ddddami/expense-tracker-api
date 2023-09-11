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
        $userId = $request->user()->id;
        $data['user_id'] = $userId;

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
            return response()->json(['message' => 'Expense not found'], Response::HTTP_NOT_FOUND);
        }
        if ($expense->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Expense does not belong to you'], Response::HTTP_FORBIDDEN);
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
        try {
            // returns 404 automatically and throws an error (not a nice message to the client) if find fails
            $expense = Expense::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return response()->json(['message' => 'Expense not found'], Response::HTTP_NOT_FOUND);
        }
        if ($expense->user_id !== $request->user()->id) {
            return response()->json(['message' => 'You do not have the permission to perform that action!'], Response::HTTP_FORBIDDEN);
        }

        $expense->update($validatedData);

        return response()->json(['message' => 'Expense updated successfully', 'expense' => $expense]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id)
    {
        try {
            // returns 404 automatically and throws an error (not a nice message to the client) if find fails
            $expense = Expense::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return response()->json(['message' => 'Expense already deleted'], Response::HTTP_NOT_FOUND);
        }
        if ($expense->user_id !== $request->user()->id) {
            return response()->json(['message' => 'You do not have the permission to perform that action!'], Response::HTTP_FORBIDDEN);
        }
        // destroy -> present on class, delete -> present on instance
        return $expense->delete();
    }
}