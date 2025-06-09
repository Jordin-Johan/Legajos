@include('partials.head')

<x-filament::page>

    <h1 class="text-2xl font-bold mb-6">Secciones de {{ $empleado->nombre }} {{ $empleado->apellido }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($secciones as $nombreSeccion => $docs)
            <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow hover:shadow-lg transition duration-300 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <span class="text-yellow-500 text-3xl">📁</span>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $nombreSeccion }}</h2>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $docs->count() }} documento{{ $docs->count() > 1 ? 's' : '' }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('filament.dashboard.resources.documentos.documentos-seccion', [   
                        'empleado' => $empleado->id,
                        'seccion' => $nombreSeccion
                    ]) }}" class="inline-block mt hover:underline font-medium">
                        Ver documentos →
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</x-filament::page>
