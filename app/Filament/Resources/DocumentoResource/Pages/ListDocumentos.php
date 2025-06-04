<?php

namespace App\Filament\Resources\DocumentoResource\Pages;

use App\Filament\Resources\DocumentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentos extends ListRecords
{
    protected static string $resource = DocumentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Crear Documento')
                ->color('info')
                ->modalHeading('Crear nuevo documento')
                ->modalWidth('2xl')
                ->slideOver()
        ];
    }
}
