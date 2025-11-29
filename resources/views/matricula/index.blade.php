@extends('layout.admin')

@section('content')
<div class="app-content-header py-6">
    <div class="container-fluid">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Gestión de Matrículas</h1>
            <div class="flex gap-2">
                <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                    <i class="bi bi-printer"></i> Reporte
                </button>
            </div>
        </div>
    </div>
</div>

<div class="app-content px-4 pb-8">
    <div class="container-fluid">
        
        <!-- FORMULARIO DE NUEVA MATRICULA -->
        <div class="bg-white rounded-xl shadow-md border-t-4 border-[#FF7F00] mb-8 overflow-hidden">
            <div class="p-6">
                <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
                    <i class="bi bi-file-earmark-plus text-[#FF7F00]"></i> Nueva Ficha de Matrícula
                </h3>
                
                <!-- RUTA CORREGIDA: Apunta al grupo 'admin' -->
                <form action="{{ route('admin.matriculas.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        <!-- Estudiante -->
                        <div class="col-span-1 lg:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Estudiante</label>
                            <select name="estudiante_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#FF7F00] focus:ring focus:ring-orange-200 focus:ring-opacity-50 h-10 px-3 bg-gray-50">
                                <option value="">-- Buscar por Código o DNI --</option>
                                <!-- Aquí debes iterar tus estudiantes desde el controlador -->
                                @foreach($estudiantes as $estudiante)
                                    <option value="{{ $estudiante->id }}">{{ $estudiante->codigo }} - {{ $estudiante->apellido }}, {{ $estudiante->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Periodo -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Periodo Académico</label>
                            <select name="periodo_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#FF7F00] focus:ring focus:ring-orange-200 focus:ring-opacity-50 h-10 px-3">
                                <option value="2025-1" selected>2025-I</option>
                                <option value="2025-2">2025-II</option>
                            </select>
                        </div>

                        <!-- Código Matrícula (Auto o Manual) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Código Matrícula</label>
                            <input type="text" name="codigo_matricula" placeholder="Ej. MAT-2025-001" 
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#FF7F00] focus:ring focus:ring-orange-200 focus:ring-opacity-50 h-10 px-3">
                        </div>

                        <!-- ID Tramite (Pago) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">N° Trámite / Recibo</label>
                            <input type="text" name="id_tramite" placeholder="Ej. 12345678" 
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#FF7F00] focus:ring focus:ring-orange-200 focus:ring-opacity-50 h-10 px-3">
                        </div>

                        <!-- Fecha Matricula -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha</label>
                            <input type="date" name="fecha_matricula" value="{{ date('Y-m-d') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#FF7F00] focus:ring focus:ring-orange-200 focus:ring-opacity-50 h-10 px-3">
                        </div>
                        
                        <!-- Estado -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Estado</label>
                            <select name="estado" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#FF7F00] focus:ring focus:ring-orange-200 focus:ring-opacity-50 h-10 px-3">
                                <option value="Matriculado">Matriculado</option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="Observado">Observado</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-[#FF7F00] hover:bg-orange-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg shadow-orange-200 transition-all transform hover:scale-105">
                            <i class="bi bi-save me-1"></i> Registrar Matrícula
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TABLA DE MATRICULADOS -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-gray-800">Estudiantes Matriculados (2025-I)</h3>
                <div class="relative">
                    <input type="text" placeholder="Buscar..." class="pl-8 pr-4 py-1 rounded-full border border-gray-300 text-sm focus:border-[#FF7F00] focus:ring-0">
                    <i class="bi bi-search absolute left-3 top-1.5 text-gray-400 text-xs"></i>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                        <tr>
                            <th class="px-6 py-3">Cod. Matrícula</th>
                            <th class="px-6 py-3">Estudiante</th>
                            <th class="px-6 py-3">Escuela</th>
                            <th class="px-6 py-3">Fecha</th>
                            <th class="px-6 py-3">N° Trámite</th>
                            <th class="px-6 py-3 text-center">Estado</th>
                            <th class="px-6 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <!-- Ejemplo de Fila (Iterar con foreach) -->
                        @foreach($matriculas as $matricula)
                        <tr class="bg-white hover:bg-orange-50/30 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $matricula->codigo_matricula }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">
                                    {{ $matricula->estudiante->apellidos ?? 'Desconocido' }}, 
                                    {{ $matricula->estudiante->nombres ?? '' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $matricula->estudiante->codigo_universitario ?? '---' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                {{ $matricula->estudiante->carrera->escuela->nombre ?? '---' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($matricula->fecha_matricula)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 font-mono text-gray-600">
                                {{ $matricula->id_tramite }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($matricula->estado == 'matriculado')
                                    <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-0.5 rounded border border-green-200">
                                        Matriculado
                                    </span>
                                @elseif($matricula->estado == 'Pendiente')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2.5 py-0.5 rounded border border-yellow-200">
                                        Pendiente
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-bold px-2.5 py-0.5 rounded border border-red-200">
                                        {{ $matricula->estado }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="#" class="text-blue-600 hover:text-blue-900 mx-1">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="#" class="text-gray-600 hover:text-gray-900 mx-1">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach

                        <!-- Fin Ejemplo -->
                    </tbody>
                </table>
            </div>
            <!-- Paginación -->
            <div class="px-6 py-4 border-t border-gray-100">
                {{-- {{ $matriculas->links() }} --}} <!-- Descomentar si usas paginación -->
            </div>
        </div>
    </div>
</div>
@endsection