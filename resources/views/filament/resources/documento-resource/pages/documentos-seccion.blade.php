<x-filament::page>
    <h1 class="text-2xl font-bold mb-6">
        Documentos de {{ $seccion }} - {{ $empleado->nombre }} {{ $empleado->apellido }}
    </h1>

    {{-- Tabla de Filament --}}
    {{ $this->table }}
</x-filament::page>
