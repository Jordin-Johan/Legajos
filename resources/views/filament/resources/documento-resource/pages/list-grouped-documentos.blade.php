@include('partials.head')

<x-filament::page>

    <h1 class="text-2xl font-bold mb-4">Lista de empleados</h1>

    <form method="GET" class="flex flex-col gap-2 md:flex-row md:justify-between md:items-center mb-6   ">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Buscar por nombre o apellido..."
                class="bg-white dark:bg-gray-900 w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md focus:ring-2 focus:ring-primary-500 focus:outline-none text-sm" />
        </div>

        <div>
            <select name="tipo"
                class="bg-white dark:bg-gray-900 w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md focus:ring-2 focus:ring-primary-500 focus:outline-none text-sm">
                <option value="">Todos</option>
                <option value="1" {{ request('tipo') == '1' ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ request('tipo') == '0' ? 'selected' : '' }}>Cesante</option>
            </select>
        </div>

        <div>
            <button type="submit"
                class="px-4 py-2 bg-primary-500 text-white rounded-md hover:bg-primary-800 transition-colors text-sm">
                Filtrar
            </button>
        </div>
    </form>


    <!-- Tarjetas de empleados -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach ($empleados as $empleado)
            <a href="{{ route('filament.dashboard.resources.documentos.secciones-empleado', ['empleado' => $empleado->id]) }}"
                class="block bg-white dark:bg-gray-900 shadow rounded-lg p-4 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 transition text-center">

                <!-- Imagen del empleado -->
                @if ($empleado->image)
                    <img src="{{ Storage::url($empleado->image) }}" alt="Foto de {{ $empleado->nombre }}"
                        class="w-16 h-16 rounded-full mx-auto mb-2 object-cover shadow-sm">
                @else
                    @php
                        $nombre = explode(' ', $empleado->nombre);
                        $apellido = explode(' ', $empleado->apellido);
                        $iniciales = strtoupper(substr($nombre[0], 0, 1) . substr($apellido[0], 0, 1));
                    @endphp
                    <div
                        class="w-16 h-16 rounded-full mx-auto mb-2 bg-gray-500 text-white flex items-center justify-center text-sm font-semibold shadow-sm">
                        {{ $iniciales }}
                    </div>
                @endif

                <!-- Nombre y Apellido -->
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ ucfirst($empleado->nombre) }}
                    {{ ucfirst($empleado->apellido) }}</h2>

                <!-- Tipo de contrato -->
                <p class="text-sm mt-1 font-semibold">
                    @if ($empleado->tipoContratado == 1)
                        <span class="text-green-500">CAS</span>
                    @elseif ($empleado->tipoContratado == 2)
                        <span class="text-blue-500">Locación</span>
                    @elseif ($empleado->tipoContratado == 3)
                        <span class="text-yellow-500">Nombrado</span>
                    @else
                        <span class="text-gray-500">Desconocido</span>
                    @endif
                </p>

                <!-- Estado de personal (Activo / Cesante) -->
                <p class="text-sm mt-1">
                    @if ($empleado->tipoPersonal == 1)
                        <span
                            class="text-green-700 bg-green-100 border border-green-500 text-xs px-2 py-1 rounded-md inline-block">Activo</span>
                    @else
                        <span
                            class="text-red-700 bg-red-100 border border-red-500 text-xs px-2 py-1 rounded-md inline-block">Cesante</span>
                    @endif
                </p>

                <!-- Cargo -->
                <p class="text-sm text-gray-500 mt-1">
                    {{ $empleado->cargo ?? 'Sin cargo' }}
                </p>
            </a>
        @endforeach
    </div>

    {{-- (Próximamente aquí agregarás paginación si deseas) --}}
    {{-- <div class="mt-6">
        {{ $empleados->links() }}
    </div> --}}
</x-filament::page>
