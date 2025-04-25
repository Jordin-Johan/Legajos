<x-filament::page>
    <h1 class="text-2xl font-bold mb-4">📁 Lista de documentos agrupados</h1>

    <!-- Buscador + Filtro -->
    <form method="GET" class="mb-6 flex flex-col md:flex-row md:items-center md:space-x-4 space-y-2 md:space-y-0">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Buscar por nombre o apellido..."
            class="w-full md:w-1/2 px-4 py-2 border border-gray-300 rounded"
        />

        <select name="tipo" class="px-4 py-2 border border-gray-300 rounded">
            <option value="">-- Todos --</option>
            <option value="1" {{ request('tipo') == '1' ? 'selected' : '' }}>Activo</option>
            <option value="0" {{ request('tipo') == '0' ? 'selected' : '' }}>Cesante</option>
        </select>

        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">
            Filtrar
        </button>
    </form>

    <!-- Tarjetas de empleados -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6">
        @forelse ($empleados as $empleado)
            <div x-data="{ open: false }" class="bg-white shadow rounded-lg p-4 border border-gray-200">
                <div class="flex items-center space-x-4 cursor-pointer" @click="open = !open">
                    <img src="{{ $empleado->image ? Storage::url($empleado->image) : asset('images/placeholder-user.png') }}"
                         alt="Foto de {{ $empleado->nombre }}"
                         class="w-16 h-16 rounded-full object-cover border border-gray-300" />
                    <div class="p-2">
                        <h2 class="text-lg font-semibold text-gray-800">{{ $empleado->nombre }} {{ $empleado->apellido }}</h2>
                        <p class="text-sm text-gray-500">{{ $empleado->cargo ?? 'Sin cargo' }}</p>
                        
                        @php
                            $contrato = match((int) $empleado->tipoContratado) {
                                1 => ['label' => 'CAS', 'color' => 'bg-primary-100 text-primary-800'],
                                2 => ['label' => 'Locación', 'color' => 'bg-blue-100 text-blue-800'],
                                3 => ['label' => 'Nombrado', 'color' => 'bg-green-100 text-green-800'],
                                4 => ['label' => 'Practicante', 'color' => 'bg-yellow-100 text-yellow-800'],
                                default => ['label' => 'Desconocido', 'color' => 'bg-gray-100 text-gray-800'],
                            };
                        @endphp

                        <p class="text-sm font-medium px-2 py-1 inline-block rounded {{ $contrato['color'] }}">
                            {{ $contrato['label'] }}
                        </p>

                        <span class="inline-block px-2 py-0.5 mt-1 text-xs font-semibold rounded-full border 
                            {{ (int) $empleado->tipoPersonal === 1 
                                ? 'text-green-600 border-green-600 bg-green-50' 
                                : 'text-red-600 border-red-600 bg-red-50' }}">
                            {!! (int) $empleado->tipoPersonal === 1 ? '✅ Activo' : '❌ Cesante' !!}
                        </span>
                    </div>
                </div>

                <div x-show="open" class="mt-4 space-y-2">
                    @php
                        $secciones = $empleado->documentos->groupBy('seccion.nombre_seccion');
                    @endphp

                    @foreach ($secciones as $nombreSeccion => $documentos)
                        <div x-data="{ showDocs: false }" class="border-t border-gray-100 pt-2">
                            <button @click="showDocs = !showDocs" class="text-sm font-bold text-gray-800 hover:text-blue-600">
                                📂 {{ $nombreSeccion }}
                            </button>

                            <ul x-show="showDocs" class="pl-4 mt-1 list-disc text-xs text-gray-600">
                                @foreach ($documentos as $doc)
                                    <li class="mb-1">
                                        📄 <strong>{{ $doc->titulo_documento }}</strong>
                                        <div class="flex gap-2 mt-1">
                                            <a href="{{ Storage::url($doc->archivo_ruta) }}" target="_blank" class="text-blue-600 hover:underline">
                                                👁 Ver
                                            </a>
                                            <a href="{{ asset('storage/' . $doc->archivo_ruta) }}" download class="text-orange-600 hover:underline">
                                                ⬇ Descargar
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="text-gray-600">No se encontraron empleados con esos filtros.</p>
        @endforelse
    </div>
</x-filament::page>
