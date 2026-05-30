<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Principal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold text-gray-700 mb-1">
                        ¡Bienvenido de nuevo, {{ Auth::user()->name }}! 👋
                    </h3>
                    <p class="text-sm text-gray-500">
                        Has iniciado sesión correctamente en el sistema de auditoría de **DataAudit Labs**.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500 flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider">
                                📋 Mis Actividades Recientes
                            </h4>
                            <span class="px-2.5 py-0.5 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">
                                Datos en Tiempo Real
                            </span>
                        </div>

                        <div class="overflow-x-auto mt-2">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tarea</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @if($tasks->isEmpty())
                                        <tr>
                                            <td colspan="2" class="px-4 py-6 text-center text-gray-400">
                                                No tienes tareas registradas actualmente en tu cuenta.
                                            </td>
                                        </tr>
                                    @else
                                        @foreach($tasks as $task)
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="font-medium text-gray-900">{{ $task->titulo }}</div>
                                                    <div class="text-xs text-gray-400 truncate max-w-xs">{{ $task->descripcion ?? 'Sin descripción' }}</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    @if($task->estado == 'completada')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            Completada
                                                        </span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                            Pendiente
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50 border-t border-gray-100">
                        <a href="{{ route('tasks.index') }}" 
                           class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-900 text-white text-sm font-medium rounded-md shadow-sm transition ease-in-out duration-150 transform hover:-translate-y-0.5">
                            ➕ Crear y Administrar Tareas
                        </a>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-gray-400 flex flex-col justify-between">
                    <div class="p-6">
                        <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-3">
                            ⚙️ Configuración de Cuenta
                        </h4>
                        <p class="text-sm text-gray-600 mb-4">
                            Actualiza la información de tu perfil de usuario, cambia tu contraseña de acceso encriptada o gestiona la eliminación segura de tu cuenta de empleado.
                        </p>
                    </div>
                    <div class="p-6 bg-gray-50 border-t border-gray-100">
                        <a href="{{ route('profile.edit') }}" 
                           class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-md shadow-sm transition ease-in-out duration-150">
                            Administrar Perfil
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>