@extends('layouts.master') {{-- Hereda del master que acabamos de crear --}}


@section('content')
<div class="app-content-header py-6">
    <div class="container-fluid">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Panel Principal</h1>
                <p class="text-gray-500 text-sm">Bienvenido al Sistema de Gestión Universitaria FIEI</p>
            </div>
            <div class="text-sm text-gray-500">
                Periodo Actual: <span class="font-bold text-[#FF7F00]">2025-I</span>
            </div>
        </div>
    </div>
</div>

<div class="app-content px-4">
    <div class="container-fluid">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Card 1 -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center text-[#FF7F00]">
                    <i class="bi bi-people-fill text-xl"></i>
                </div>
                <div>
                    <div class="text-gray-500 text-sm font-medium">Estudiantes</div>
                    <div class="text-2xl font-bold text-gray-800">213</div>
                </div>
            </div>
            
            <!-- Card 2 -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                    <i class="bi bi-pencil-square text-xl"></i>
                </div>
                <div>
                    <div class="text-gray-500 text-sm font-medium">Matrículas Hoy</div>
                    <div class="text-2xl font-bold text-gray-800">43</div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                    <i class="bi bi-person-workspace text-xl"></i>
                </div>
                <div>
                    <div class="text-gray-500 text-sm font-medium">Docentes Activos</div>
                    <div class="text-2xl font-bold text-gray-800">13</div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                    <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                </div>
                <div>
                    <div class="text-gray-500 text-sm font-medium">Observados</div>
                    <div class="text-2xl font-bold text-gray-800">15</div>
                </div>
            </div>
        </div>

        <!-- Section: Accesos Rápidos o Noticias -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-lg mb-4 text-gray-800 border-b pb-2">Estado de mi Cuenta</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600">Usuario:</span>
                        <span class="font-medium">{{ auth()->user()->name ?? 'Administrador' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600">Rol:</span>
                        <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">Administrador</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600">Último acceso:</span>
                        <span class="font-medium">{{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-[#FF7F00] to-orange-700 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="font-bold text-xl mb-2">Proceso de Matrícula 2025-I</h3>
                    <p class="text-white/90 mb-6">El proceso de matrícula regular finaliza el 30 de Marzo. Asegúrese de validar todos los pagos.</p>
                    <a href="{{ url('/matriculas') }}" class="bg-white text-[#FF7F00] px-4 py-2 rounded-lg font-bold hover:bg-gray-100 transition-colors inline-block">
                        Ir a Matrículas
                    </a>
                </div>
                <i class="bi bi-calendar-check absolute -bottom-4 -right-4 text-9xl text-white/10 rotate-12"></i>
            </div>
        </div>
    </div>
</div>
@endsection