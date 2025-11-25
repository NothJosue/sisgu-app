@extends('layout.admin')

@section('content')
<div class="container-fluid">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Panel del Profesor</h3>
        </div>
        <div class="card-body">
            <h1>Profesor: {{ $profesor->nombres }} {{ $profesor->apellidos }}</h1>
            <p>Correo Institucional: {{ $profesor->correo_institucional }}</p>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
            </form>
        </div>
    </div>
</div>
@endsection