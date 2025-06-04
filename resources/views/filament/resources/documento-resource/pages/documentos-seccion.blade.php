@include('partials.head')

<x-filament::page>

    <h1 class="text-2xl font-bold text-accent-content p-4 rounded mb-6 shadow-sm">
        Documentos de {{ $seccion }} - {{ strtolower($empleado->nombre) }} {{ strtolower($empleado->apellido) }}
    </h1>

    {{-- Contenedor con scroll horizontal para tablas anchas --}}
    <div class="overflow-x-auto rounded-lg shadow border border-gray-200 bg-white">

        {{-- Tabla con encabezado estilizado --}}
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100 text-gray-600 uppercase">
                <tr>
                    <th class="px-6 py-3 text-left">Título</th>
                    <th class="px-6 py-3 text-left">Descripción</th>
                    <th class="px-6 py-3 text-center">Estado</th>
                    <th class="px-6 py-3 text-center">Creado</th>
                    <th class="px-6 py-3 text-center">Modificado</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 text-gray-700">
                @forelse ($documentos as $doc)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 max-w-xs break-words">{{ $doc->titulo_documento }}</td>
                        <td class="px-6 py-4">{{ $doc->descripcion_documento }}</td>
                        <td class="px-6 py-4 text-center">
                            @if ($doc->estado_documento)
                                <span class="text-green-600 text-lg">✅ activo</span>
                            @else
                                <span class="text-red-600 text-lg">❌</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">{{ $doc->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-center">{{ $doc->updated_at->format('d/m/Y') }}</td>

                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <div class="flex flex-wrap justify-center gap-2">
                                <a href="{{ Storage::url($doc->archivo_ruta) }}" target="_blank"
                                    class="bg-rose-500 hover:bg-orange-100 text-white transition-colors text-sm px-4 py-2 rounded-md inline-block">
                                    Ver </a> 
                                <a href="{{ asset('storage/' . $doc->archivo_ruta) }}" download
                                    class="bg-rose-500 hover:bg-orange-100 text-white transition-colors text-sm px-4 py-2 rounded-md inline-block">
                                    Descargar</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-6 text-center text-gray-400">
                            No hay documentos disponibles en esta sección.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Separador visual o información adicional (opcional, puedes eliminarlo) --}}
    {{-- <div class="bg-lime-600 text-white p-4 mt-4 rounded shadow">TEST COLOR</div> --}}

    {{-- Paginación al estilo Filament --}}
    <div class="mt-6">
        {{ $documentos->links() }}
    </div>

</x-filament::page>
