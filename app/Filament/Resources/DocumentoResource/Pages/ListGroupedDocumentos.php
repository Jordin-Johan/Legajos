<?php

namespace App\Filament\Resources\DocumentoResource\Pages;

use App\Filament\Resources\DocumentoResource;
use App\Models\Empleado;
use Filament\Resources\Pages\Page;

class ListGroupedDocumentos extends Page
{
    protected static string $resource = DocumentoResource::class;

    protected static string $view = 'filament.resources.documento-resource.pages.list-grouped-documentos';

    public $empleados;

    public function mount()
    {
        $query = Empleado::with(['documentos.seccion']);

        if (request()->filled('search')) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%' . request('search') . '%')
                  ->orWhere('apellido', 'like', '%' . request('search') . '%');
            });
        }

        if (request()->filled('tipo')) {
            $query->where('tipoPersonal', request('tipo'));
        }

        // ⚠️ Aquí ya NO usar paginate(), porque no puedes paginar con propiedades públicas
        $this->empleados = $query->get();
    }
}
