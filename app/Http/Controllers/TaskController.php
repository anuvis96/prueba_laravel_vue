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
        // Obtiene tareas solo del usuario logueado
        $tasks = Task::where('user_id', Auth::id())->get();

        // Retorna vista Inertia con las tareas
        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
        ]);
    }

    // Guardar una nueva tarea validando datos
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',  // Título obligatorio con máximo 255 caracteres
            'description' => 'nullable',     // Descripción opcional
        ]);

        // Crea tarea asociada al usuario autenticado
        Auth::user()->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // Redirige a lista con mensaje de éxito
        return redirect()->route('tasks.index')->with('success', 'Tarea creada correctamente.');
    }

    // Actualizar tarea (solo si pertenece al usuario autenticado)
    public function update(Request $request, Task $task)
    {
        // Validar que el usuario es propietario de la tarea
        if ($task->user_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
        ]);

        // Actualiza la tarea con nuevos datos
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // Redirige con mensaje de éxito
        return redirect()->route('tasks.index')->with('success', 'Tarea actualizada correctamente.');
    }

    // Eliminar tarea (solo si pertenece al usuario autenticado)
    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        // Borra la tarea
        $task->delete();

        // Redirige con mensaje de éxito
        return redirect()->route('tasks.index')->with('success', 'Tarea eliminada correctamente.');
    }
}
