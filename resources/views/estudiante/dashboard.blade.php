@extends('layout.admin') {{-- Asumiendo que usas admin.blade.php como base, o crea un layout master --}}

@section('content')
<div class="container-fluid">
    <div class="card card-success">
        <div class="card-header">
            <h3 class="card-title">Bienvenido Estudiante</h3>
        </div>
        <div class="card-body">
            <h1>Hola, {{ $estudiante->nombres }} {{ $estudiante->apellidos }}</h1>
            <p>Tu código de estudiante es: <strong>{{ $estudiante->codigo_estudiante }}</strong></p>
            <p>Tu carrera es: <strong>{{ $estudiante->carrera->nombre ?? 'Sin carrera asignada' }}</strong></p>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
            </form>
        </div>
    </div>
</div>
@endsection