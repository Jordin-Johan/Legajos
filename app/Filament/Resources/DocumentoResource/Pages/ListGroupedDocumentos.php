<?php

namespace App\Filament\Resources\DocumentoResource\Pages;

use App\Filament\Resources\DocumentoResource;
use App\Models\Empleado;
use Filament\Resources\Pages\Page;

class ListGroupedDocumentos extends Page
{
    protected static string $resource = DocumentoResource::class;
    protected static string $view = 'filament.resources.documento-resource.pages.list-grouped-documentos';
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationGroup = 'Gestión de Documentos';
    protected static ?string $navigationLabel = 'Documentos por Sección';
    protected static ?string $slug = 'agrupados';

    public function getTitle(): string
    {
        return 'Documentos por sección';
    }

    public function getViewData(): array
    {
        // Mostrar TODOS los empleados creados, no solo los que tienen documentos
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

        $empleados = $query->paginate(10);

        return [
            'empleados' => $empleados,
        ];
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return true;
    }
}