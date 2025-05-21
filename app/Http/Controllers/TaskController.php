<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Muestra la lista de tareas del usuario autenticado
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->get();

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
        ]);
    }

    // Guarda una nueva tarea creada por el usuario
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
        ]);

        Auth::user()->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Tarea creada correctamente.');
    }

    // Actualiza la tarea existente con los datos enviados
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Tarea actualizada correctamente.');
    }

    // Elimina una tarea específica
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->back()->with('success', 'Tarea eliminada correctamente.');
    }
}
