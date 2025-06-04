<?php

namespace App\Filament\Resources\DocumentoResource\Pages;

use App\Filament\Resources\DocumentoResource;
use App\Models\Documento;
use App\Models\Empleado;
use Filament\Resources\Pages\Page;

class DocumentosSeccion extends Page
{
    protected static string $resource = DocumentoResource::class;
    protected static string $view = 'filament.resources.documento-resource.pages.documentos-seccion';

    public $empleado;
    public $seccion;
    // 🔥 ELIMINA esto: public $documentos;

    public function mount($empleado, $seccion)
    {
        $this->empleado = Empleado::findOrFail($empleado);
        $this->seccion = $seccion;
    }

    public function getViewData(): array
    {
        // Aquí sí haces la consulta
        $documentos = Documento::where('empleado_id', $this->empleado->id)
            ->whereHas('seccion', function ($query) {
                $query->where('nombre_seccion', $this->seccion);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return [    
            'empleado' => $this->empleado,
            'seccion' => $this->seccion,
            'documentos' => $documentos, 
        ];
    }
}
