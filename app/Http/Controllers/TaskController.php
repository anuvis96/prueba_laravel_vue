<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Mostrar todas las tareas del usuario autenticado
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->get();

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
        ]);
    }

    // Guardar una nueva tarea
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

    // Actualizar tarea (solo si es del usuario autenticado)
    public function update(Request $request, Task $task)
    {
        // Validar que la tarea pertenezca al usuario
        if ($task->user_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Tarea actualizada correctamente.');
    }

    // Eliminar tarea (solo si es del usuario autenticado)
    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tarea eliminada correctamente.');
    }
}
