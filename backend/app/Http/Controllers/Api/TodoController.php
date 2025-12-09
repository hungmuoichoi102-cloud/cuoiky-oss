<?php

namespace App\Http\Controllers\Api;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'data' => Todo::latest()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $todo = Todo::create($validated);

        return response()->json([
            'data' => $todo,
            'message' => 'Todo created successfully',
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        return response()->json([
            'data' => $todo,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'is_completed' => 'sometimes|boolean',
        ]);

        $todo->update($validated);

        return response()->json([
            'data' => $todo,
            'message' => 'Todo updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();

        return response()->json([
            'message' => 'Todo deleted successfully',
        ]);
    }

    /**
     * Toggle the completion status of a todo.
     */
    public function toggle(Todo $todo)
    {
        $todo->update([
            'is_completed' => !$todo->is_completed,
        ]);

        return response()->json([
            'data' => $todo,
            'message' => 'Todo toggled successfully',
        ]);
    }
}
