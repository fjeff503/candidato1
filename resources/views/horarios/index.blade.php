@extends('layouts.app')

@section('content')
    <h2 class="h1 text-center">Gestión de Horarios</h2>
    @livewire('horarios.horario-create')
    @livewire('horarios.horarios-index')
@endsection
