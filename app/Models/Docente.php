<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    protected $table = 'docentes';

    protected $fillable = [
        'nombre_completo',
        'correo',
        'fecha_ingreso',
    ];

    // Relaciones
    public function horarios()
    {
        return $this->hasMany(Horario::class, 'id_docente');
    }
}
