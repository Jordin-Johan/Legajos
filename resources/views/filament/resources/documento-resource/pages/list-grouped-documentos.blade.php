@include('partials.head')

<x-filament::page>

    <h1 class="text-2xl font-bold mb-4">Lista de empleados</h1>

    <form method="GET" class="w-full max-w-5xl mx-auto flex flex-col gap-2 md:flex-row md:items-center md:gap-4 mb-6">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Buscar por nombre o apellido..."
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:outline-none text-sm" />
        </div>

        <div>
            <select name="tipo"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:outline-none text-sm">
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
                class="block bg-white shadow rounded-lg p-4 border border-gray-200 hover:bg-gray-50 transition text-center">

                <!-- Imagen del empleado -->
                @if ($empleado->image)
                    <img src="{{ Storage::url($empleado->image) }}" alt="Foto de {{ $empleado->nombre }}"
                        class="w-16 h-16 rounded-full mx-auto mb-2 object-cover shadow-sm">
                @else
                    <div
                        class="w-16 h-16 rounded-full mx-auto mb-2 bg-gray-200 flex items-center justify-center text-gray-400 shadow-sm">
                        📷
                    </div>
                @endif


                <!-- Nombre y Apellido -->
                <h2 class="text-lg font-semibold text-gray-800">{{ ucfirst($empleado->nombre) }}
                    {{ ucfirst($empleado->apellido) }}</h2>

                <!-- Tipo de contrato -->
                <p class="text-sm mt-1 font-semibold">
                    @if ($empleado->tipoContratado == 1)
                        <span class="text-green-500">CAS</span>
                    @elseif ($empleado->tipoContratado == 2)
                        <span class="text-blue-500">Locación</span>
                    @elseif ($empleado->tipoContratado == 3)
                        <span class="text-yellow-500">Nombrado</span>
                    @elseif ($empleado->tipoContratado == 4)
                        <span class="text-red-500">Practicante</span>
                    @else
                        <span class="text-gray-500">Desconocido</span>
                    @endif
                </p>

                <!-- Estado de personal (Activo / Cesante) -->
                <p class="text-sm mt-1">
                    @if ($empleado->tipoPersonal == 1)
                        <span class="text-green-600 text-xl">✅</span>
                    @else
                        <span class="text-red-600 text-xl">❌</span>
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
