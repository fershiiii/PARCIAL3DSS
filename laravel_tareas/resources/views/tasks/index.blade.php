<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Tareas - DataAudit Labs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Crear Nueva Tarea</h3>
                    
                    <form action="{{ route('tasks.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="titulo" :value="__('Título de la Tarea')" />
                            <x-text-input id="titulo" name="titulo" type="text" class="mt-1 block w-full" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('titulo')" />
                        </div>

                        <div>
                            <x-input-label for="descripcion" :value="__('Descripción (Opcional)')" />
                            <textarea id="descripcion" name="descripcion" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('descripcion')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Guardar Tarea') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Listado de Actividades</h3>

                @if($tareas->isEmpty())
                    <p class="text-gray-500">No tienes tareas registradas actualmente.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($tareas as $tarea)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $tarea->titulo }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $tarea->descripcion ?? 'Sin descripción' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $tarea->estado == 'completada' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($tarea->estado) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <form action="{{ route('tasks.update', $tarea->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="titulo" value="{{ $tarea->titulo }}">
                                                <input type="hidden" name="estado" value="{{ $tarea->estado == 'pendiente' ? 'completada' : 'pendiente' }}">
                                                <button type="submit" class="text-indigo-600 hover:text-indigo-900 mr-2">
                                                    {{ $tarea->estado == 'pendiente' ? 'Completar' : 'Reabrir' }}
                                                </button>
                                            </form>

                                            <form action="{{ route('tasks.destroy', $tarea->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas borrar esta tarea?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>