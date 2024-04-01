<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    // Listar todas as tarefas
    public function index()
    {
        $tasks = auth()->user()->tasks;
        return response()->json($tasks);
    }

    // Criar uma nova tarefa
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $task = auth()->user()->tasks()->create($validator->validated());
        return response()->json($task, 201);
    }

    // Obter uma única tarefa
    public function show($id)
{
    $task = auth()->user()->tasks()->find($id);

    if (!$task) {
        return response()->json(['message' => 'Task not found'], 404);
    }

    return response()->json($task);
}

    // Atualizar uma tarefa existente
    public function update(Request $request, $id)
    {
        $task = auth()->user()->tasks()->find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
    

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $task->update($validator->validated());
        return response()->json($task);
    }

    // Excluir uma tarefa
    public function destroy($id)
{
    $task = auth()->user()->tasks()->find($id);

    if (!$task) {
        return response()->json(['message' => 'Task not found'], 404);
    }

    $task->delete();

    return response()->json(['message' => 'Task deleted'], 200);
}
}
