<?php

namespace App\Filament\Resources\DocumentoResource\Pages;

use App\Filament\Resources\DocumentoResource;
use App\Models\Documento;
use App\Models\Empleado;
use App\Models\Seccion;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;

class DocumentosSeccion extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = DocumentoResource::class;
    protected static string $view = 'filament.resources.documento-resource.pages.documentos-seccion';
    protected static ?int $navigationSort = 4;

    public $empleado;
    public $seccion;

    public function mount($empleado, $seccion)
    {
        $this->empleado = Empleado::findOrFail($empleado);
        $this->seccion = urldecode($seccion);
    }

    public function getViewData(): array
    {
        return [
            'empleado' => $this->empleado,
            'seccion' => $this->seccion,
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Documento::query()
                    ->where('empleado_id', $this->empleado->id)
                    ->whereHas('seccion', fn($q) => $q->whereRaw('LOWER(nombre_seccion) = ?', [strtolower($this->seccion)]))
            )
            ->columns([
                Tables\Columns\TextColumn::make('titulo_documento')->label('Título')->searchable(),
                Tables\Columns\TextColumn::make('descripcion_documento')->label('Descripción')->limit(50),
                Tables\Columns\IconColumn::make('estado_documento')->label('Estado')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('Creado')->date(),
                Tables\Columns\TextColumn::make('updated_at')->label('Modificado')->date(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make('crear')
                    ->label('Subir Documento')
                    ->icon('heroicon-o-plus')
                    ->slideOver()
                    ->form([
                        Forms\Components\TextInput::make('titulo_documento')
                            ->label('Título del Documento')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('descripcion_documento')
                            ->label('Descripción')
                            ->maxLength(500),
                        Forms\Components\FileUpload::make('archivo_ruta')
                            ->label('Archivo')
                            ->required()
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(10240) // 10MB
                            ->directory('documentos')
                            ->preserveFilenames(),
                        // Forms\Components\Toggle::make('estado_documento')
                        //     ->label('Activo')
                        //     ->default(true),
                    ])
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['empleado_id'] = $this->empleado->id;
                        $data['seccion_id'] = Seccion::where('nombre_seccion', $this->seccion)->first()?->id;

                        // Si descripción está vacía, asignar texto por defecto
                        if (empty($data['descripcion_documento'])) {
                            $data['descripcion_documento'] = 'Sin descripción';
                        }

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Documento subido')
                            ->body('El documento se ha subido correctamente.')
                    ),
            ])
            ->actions([
                Tables\Actions\Action::make('ver')
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => asset('storage/' . $record->archivo_ruta))
                    ->openUrlInNewTab()
                    ->color('green'),

                Tables\Actions\Action::make('descargar')
                    ->label('')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($record) {
                        return response()->download(storage_path('app/public/' . $record->archivo_ruta));
                    }),

                Tables\Actions\EditAction::make('editar')
                    ->label('')
                    ->icon('heroicon-o-pencil')
                    ->color('warning')
                    ->slideOver()
                    ->form([
                        Forms\Components\TextInput::make('titulo_documento')
                            ->label('Título del Documento')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('descripcion_documento')
                            ->label('Descripción')
                            ->maxLength(500),
                        Forms\Components\FileUpload::make('archivo_ruta')
                            ->label('Archivo')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(10240) // 10MB
                            ->directory('documentos')
                            ->preserveFilenames(),
                        Forms\Components\Toggle::make('estado_documento')
                            ->label('Activo'),
                    ])
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Documento actualizado')
                            ->body('El documento se ha actualizado correctamente.')
                    ),
                Tables\Actions\DeleteAction::make('eliminar')
                    ->label('')
                    ->icon('heroicon-o-trash')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Documento eliminado')
                            ->body('El documento se ha eliminado correctamente.')
                    ),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label('Eliminar seleccionados'),
            ]);
    }

    public function getBreadcrumbs(): array
    {
        return [
            $this->getResource()::getUrl('agrupados') => 'Documentos por sección',
            $this->getResource()::getUrl('secciones-empleado', ['empleado' => $this->empleado->id]) => 'Secciones Empleado',
            '' => 'Documentos',
        ];
    }
}
