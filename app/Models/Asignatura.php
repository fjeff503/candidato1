<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    use HasFactory;

    protected $table = 'asignaturas';

    protected $fillable = [
        'nombre',
        'codigo',
    ];

    // Relaciones
    public function horarios()
    {
        return $this->hasMany(Horario::class, 'id_asignatura');
    }
}
