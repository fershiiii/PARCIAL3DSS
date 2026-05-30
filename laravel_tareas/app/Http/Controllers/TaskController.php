<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task; // No olvides importar el modelo para que funcione

class TaskController extends Controller
{
    /**
     * Muestra el listado de tareas del usuario autenticado.
     */
    public function index()
    {
        // Trae solo las tareas del usuario logueado
        $tareas = auth()->user()->tasks()->latest()->get();
        
        // Retorna la vista pasando la variable $tareas
        return view('tasks.index', compact('tareas'));
    }

  public function store(Request $request)
{
    // Validación de formulario
    $request->validate([
        'titulo' => 'required|string|max:150',
        'descripcion' => 'nullable|string',
    ]);

    // Crear la tarea asociada al usuario actual
    auth()->user()->tasks()->create([
        'titulo' => $request->titulo,
        'descripcion' => $request->descripcion,
        'estado' => 'pendiente',
    ]);

    return redirect()->route('tasks.index')->with('success', 'Tarea creada exitosamente.');
}

    public function update(Request $request, Task $task)
{
    // Bloqueo de seguridad: Si la tarea no es del usuario logueado, aborta con un error 403
    if ($task->user_id !== auth()->id()) {
        abort(403, 'Acción no autorizada.');
    }

    $request->validate([
        'titulo' => 'required|string|max:150',
        'descripcion' => 'nullable|string',
        'estado' => 'required|in:pendiente,completada',
    ]);

    $task->update($request->only(['titulo', 'descripcion', 'estado']));

    return redirect()->route('tasks.index')->with('success', 'Tarea actualizada con éxito.');
}

public function destroy(Task $task)
{
    if ($task->user_id !== auth()->id()) {
        abort(403, 'Acción no autorizada.');
    }

    $task->delete();

    return redirect()->route('tasks.index')->with('success', 'Tarea eliminada.');
}
}