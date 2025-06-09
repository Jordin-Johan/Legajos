<?php

namespace App\Filament\Resources\DocumentoResource\Pages;

use App\Filament\Resources\DocumentoResource;
use App\Models\Empleado;
use App\Models\Seccion; // Agregar este import
use Filament\Resources\Pages\Page;

class SeccionesEmpleado extends Page
{
    protected static string $resource = DocumentoResource::class;
    protected static string $view = 'filament.resources.documento-resource.pages.secciones-empleado';

    public $empleado;

    public function mount($empleado)
    {
        $this->empleado = Empleado::with('documentos.seccion')->findOrFail($empleado);
    }

    public function getViewData(): array
    {
        // Obtener todas las secciones de la tabla seccions
        $todasLasSecciones = Seccion::all();
        
        // Agrupar documentos existentes por sección
        $documentosAgrupados = $this->empleado->documentos
            ->groupBy(fn($doc) => optional($doc->seccion)->nombre_seccion ?? 'Sin Sección');
        
        // Crear array con todas las secciones (con o sin documentos)
        $seccionesCompletas = collect();
        
        foreach ($todasLasSecciones as $seccion) {
            $nombreSeccion = $seccion->nombre_seccion;
            $documentos = $documentosAgrupados->get($nombreSeccion, collect()); // Colección vacía si no hay documentos
            
            $seccionesCompletas->put($nombreSeccion, $documentos);
        }
        
        // Agregar documentos sin sección al final (si los hay)
        if ($documentosAgrupados->has('Sin Sección')) {
            $seccionesCompletas->put('Sin Sección', $documentosAgrupados->get('Sin Sección'));
        }

        return [
            'empleado' => $this->empleado,
            'secciones' => $seccionesCompletas,
        ];
    }
    
    public function getBreadcrumbs(): array
    {
        return [
            $this->getResource()::getUrl('agrupados') => 'Documentos por sección',
            '' => 'Secciones Empleado',
        ];
    }
}