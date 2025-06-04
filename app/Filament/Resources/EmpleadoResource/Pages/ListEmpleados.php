<?php

namespace App\Filament\Resources\EmpleadoResource\Pages;

use App\Filament\Resources\EmpleadoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmpleados extends ListRecords
{
    protected static string $resource = EmpleadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Crear Empleado')
                ->color('info')
                ->modalHeading('Crear nuevo empleado')
                ->modalWidth('2xl')
                ->slideOver()
        ];
    }
}
