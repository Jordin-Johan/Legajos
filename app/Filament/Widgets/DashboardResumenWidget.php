<?php

namespace App\Filament\Widgets;

use App\Models\Empleado;
use App\Models\Documento;
use App\Models\Seccion;
use Filament\Widgets\Widget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget;
use Asantibanez\LivewireCharts\LivewireColumnChart;

class DashboardResumenWidget extends StatsOverviewWidget
{
    protected static ?string $pollingInterval = null;
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total de Empleados', Empleado::count())
                ->description('Incluye activos y cesantes')
                ->icon('heroicon-o-user-group')
                ->color('primary'),

            Stat::make('Activos', Empleado::where('tipoPersonal', 1)->count())
                ->description('Empleados en actividad')
                ->icon('heroicon-o-user')
                ->color('success'),

            Stat::make('Cesantes', Empleado::where('tipoPersonal', 0)->count())
                ->description('Empleados cesantes')
                ->icon('heroicon-o-user-minus')
                ->color('danger'),

            Stat::make('Documentos Subidos', Documento::count())
                ->description('Cantidad total de archivos')
                ->icon('heroicon-o-document')
                ->color('warning'),

            Stat::make('Secciones Creadas', Seccion::count())
                ->description('Todas las secciones')
                ->icon('heroicon-o-folder')
                ->color('info'),
        ];
    }
}
