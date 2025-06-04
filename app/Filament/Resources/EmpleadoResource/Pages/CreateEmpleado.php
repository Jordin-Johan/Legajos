<?php

namespace App\Filament\Resources\EmpleadoResource\Pages;

use App\Filament\Resources\EmpleadoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmpleado extends CreateRecord
{
    protected static string $resource = EmpleadoResource::class;

    public function getTitle(): string
    {
        return 'Registrar Empleado';
    }
}
