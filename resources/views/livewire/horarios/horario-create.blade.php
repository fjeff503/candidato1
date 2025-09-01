<div class="m-4 p-4">
    <form wire:submit.prevent="save" class="space-y-4">

        <!-- Correo -->
        <div>
            <label for="correo" class="block font-semibold mb-1">Correo del Docente</label>
            <input type="email" id="correo" name="correo" wire:model.defer="correo" placeholder="Correo"
                class="border p-2 rounded w-full" required>
            @error('correo')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Asignatura -->
        <div>
            <label for="id_asignatura" class="block font-semibold mb-1">Asignatura</label>
            <select id="id_asignatura" name="id_asignatura" wire:model.defer="id_asignatura"
                class="border p-2 rounded w-full" required>
                <option value="">Selecciona Asignatura</option>
                @foreach ($asignaturas as $asignatura)
                    <option value="{{ $asignatura->codigo }}">
                        {{ $asignatura->nombre }} ({{ $asignatura->codigo }})
                    </option>
                @endforeach
            </select>
            @error('id_asignatura')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Día de la Semana -->
        <div>
            <label for="dia_semana" class="block font-semibold mb-1">Día de la Semana</label>
            <select id="dia_semana" name="dia_semana" wire:model.defer="dia_semana" class="border p-2 rounded w-full"
                required>
                <option value="">Selecciona Día</option>
                <option value="Lunes">Lunes</option>
                <option value="Martes">Martes</option>
                <option value="Miércoles">Miércoles</option>
                <option value="Jueves">Jueves</option>
                <option value="Viernes">Viernes</option>
                <option value="Sábado">Sábado</option>
                <option value="Domingo">Domingo</option>
            </select>
            @error('dia_semana')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Hora Inicio -->
        <div>
            <label for="hora_inicio" class="block font-semibold mb-1">Hora Inicio</label>
            <input type="time" id="hora_inicio" name="hora_inicio" wire:model.defer="hora_inicio"
                class="border p-2 rounded w-full" required>
            @error('hora_inicio')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Hora Fin -->
        <div>
            <label for="hora_fin" class="block font-semibold mb-1">Hora Fin</label>
            <input type="time" id="hora_fin" name="hora_fin" wire:model.defer="hora_fin"
                class="border p-2 rounded w-full" required>
            @error('hora_fin')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <button type="submit" class="bg-blue-500 text-white px-20 py-2 rounded hover:bg-blue-600">
                Agregar
            </button>
        </div>

    </form>
</div>
