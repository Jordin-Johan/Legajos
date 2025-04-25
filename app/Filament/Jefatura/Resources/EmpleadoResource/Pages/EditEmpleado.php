<?php

namespace App\Filament\Jefatura\Resources\EmpleadoResource\Pages;

use App\Filament\Jefatura\Resources\EmpleadoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmpleado extends EditRecord
{
    protected static string $resource = EmpleadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
