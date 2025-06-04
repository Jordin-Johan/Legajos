<?php

namespace App\Filament\Resources\DocumentoResource\Pages;

use App\Filament\Resources\DocumentoResource;
use App\Models\Empleado;
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
        return [
            'empleado' => $this->empleado,
            'secciones' => $this->empleado->documentos
                ->groupBy(fn($doc) => optional($doc->seccion)->nombre_seccion ?? 'Sin Sección'),
        ];
    }
}
