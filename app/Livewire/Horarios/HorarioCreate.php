<?php

namespace App\Livewire\Horarios;

use App\Models\Asignatura;
use Livewire\Component;

use Illuminate\Support\Facades\DB;

class HorarioCreate extends Component
{
    public $correo, $id_asignatura, $dia_semana, $hora_inicio, $hora_fin;

    protected $rules = [
        'correo' => 'required|email',
        'id_asignatura' => 'required|string',
        'dia_semana' => 'required|string',
        'hora_inicio' => 'required|date_format:H:i',
        'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
    ];

    public function save()
{
    $this->validate();

    try {
        DB::statement('EXEC sp_asignar_horario @correo_docente = ?, @codigo_asignatura = ?, @dia_semana = ?, @hora_inicio = ?, @hora_fin = ?',
            [
                $this->correo,
                $this->id_asignatura,
                $this->dia_semana,
                $this->hora_inicio,
                $this->hora_fin
            ]
        );

        // Solo aquí se dispara el alert de éxito
        $this->dispatch('showAlert', [
            'type' => 'success',
            'message' => 'Horario agregado correctamente!'
        ]);

        session()->flash('message', 'Horario asignado correctamente.');

        // Limpiar formulario
        $this->reset(['correo', 'id_asignatura', 'dia_semana', 'hora_inicio', 'hora_fin']);

        // Actualizar tabla si tienes otro componente
        $this->dispatch('horarioAdded');

    } catch (\Illuminate\Database\QueryException $e) {
        // Captura errores del SP
        $mensaje = $e->getMessage();

        if (strpos($mensaje, 'Ya existe un horario') !== false) {
            $errorMsg = 'Ya existe un horario para este docente en la misma franja horaria.';
        } elseif (strpos($mensaje, 'no existe') !== false) {
            $errorMsg = $mensaje;
        } else {
            $errorMsg = 'Ocurrió un error al asignar el horario.';
        }

        // Mostrar SweetAlert de error
        $this->dispatch('showAlert', [
            'type' => 'error',
            'message' => $errorMsg
        ]);

        // También opcional: mostrar en sesión
        session()->flash('error', $errorMsg);
    }
}

    public function render()
    {
        $asignaturas = Asignatura::all(); // para llenar el select

        return view('livewire.horarios.horario-create', compact('asignaturas'));
    }
}
