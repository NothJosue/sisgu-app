@extends('layouts.master') {{-- Hereda del master que acabamos de crear --}}

@section('title', 'Panel Estudiante') {{-- Cambia el título de la pestaña --}}

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h3 class="mb-0">Resumen Académico</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                <li class="breadcrumb-item active">Panel</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    Hola, {{ $estudiante->nombres }}. Bienvenido al SISGU.
                </div>
            </div>
        </div>
    </div>
@endsection