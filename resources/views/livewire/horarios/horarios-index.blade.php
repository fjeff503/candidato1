<div> <!-- Único contenedor raíz -->

    <div class="mb-4">
        <input type="text" wire:model.debounce.300ms="searchCorreo" placeholder="Filtrar por correo"
            class="border p-2 rounded w-full" />
    </div>


    <!-- Tabla de estudiantes -->
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>N°</th>
                <th>Correo</th>
                <th>Asignatura</th>
                <th>D&iacute;a</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($horarios as $horario)
                <tr>
                    <td>{{ $loop->iteration + ($horarios->currentPage() - 1) * $horarios->perPage() }}</td>
                    <td>{{ $horario->correo_docente }}</td>
                    <td>{{ $horario->codigo_asignatura }}</td>
                    <td>{{ $horario->dia_semana }}</td>
                    <td>{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}</td>
                    <td><button type="button" wire:click="confirmDelete({{ $horario->id_horario }})"
                            class="btn btn-danger btn-sm">
                            Eliminar
                        </button></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    {{ $horarios->links() }}
    <br>


    <!-- Scripts SweetAlert -->
    <script>
        window.addEventListener('confirmDelete', () => {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete();
                }
            });
        });

        window.addEventListener('showAlert', event => {
            const data = event.detail[0];
            Swal.fire({
                icon: data.type,
                title: data.message,
                showConfirmButton: true,
            });
        });
    </script>

</div> <!-- Fin del contenedor raíz -->
