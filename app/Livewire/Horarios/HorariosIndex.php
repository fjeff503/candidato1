<?php

namespace App\Livewire\Horarios;

use App\Models\Horario;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class HorariosIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $searchCorreo = '';
    public $perPage = 10;
    public $horarioToDelete = null;

    //listeners para manejar mensajes sweetAlert
    protected $listeners = [
        'horarioAdded' => '$refresh',
        'horarioUpdated' => '$refresh',
        'refreshComponent' => '$refresh',
    ];

    protected $paginationTheme = 'bootstrap';

    //Resetea la paginación al actualizar búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

protected $queryString = ['searchCorreo']; // para mantener filtro en URL

// Esto reinicia la paginación cuando cambia el filtro
public function updatingSearchCorreo()
{
    $this->resetPage();
}

public function render()
{
    $horarios = DB::table('vw_horarios_docentes')
        ->when($this->searchCorreo, fn($query) =>
            $query->where('correo_docente', 'like', '%' . $this->searchCorreo . '%')
        )
        ->select(
            'nombre_docente',
            'correo_docente',
            'nombre_asignatura',
            'codigo_asignatura',
            'dia_semana',
            'hora_inicio',
            'hora_fin',
            'id_horario'
        )
        ->orderBy('id_horario', 'desc')
        ->paginate($this->perPage)
        ->withQueryString(); // importante para actualizar paginación con filtro

    return view('livewire.horarios.horarios-index', compact('horarios'));
}




    // Método para mostrar SweetAlert de confirmación
    public function confirmDelete($id)
    {
        $this->horarioToDelete = $id;
        $this->dispatch('confirmDelete');
    }

    // Método para eliminar horario
    public function delete()
    {
        $horario = Horario::findOrFail($this->horarioToDelete);
        $horario->delete();

        $this->resetPage();
        $this->horarioToDelete = null;

        $this->dispatch('showAlert', [
            'type' => 'success',
            'message' => 'Horario eliminado correctamente!'
        ]);
    }
}


