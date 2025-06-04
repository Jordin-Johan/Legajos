<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentoResource\Pages;
use App\Filament\Resources\DocumentoResource\RelationManagers;
use App\Models\Documento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ActionColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Filament\Navigation\NavigationItem;

class DocumentoResource extends Resource
{
    protected static ?string $model = Documento::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Gestión de Documentos';
    protected static ?string $navigationLabel = 'Documentos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('titulo_documento')
                    ->label('Título del documento')
                    ->required(),

                Textarea::make('descripcion_documento')
                    ->label('Descripción del documento')
                    ->required()
                    ->maxLength(250),

                FileUpload::make('archivo_ruta')
                    ->label('Archivo PDF')
                    ->disk('public')
                    ->directory('documentos')
                    ->acceptedFileTypes(['application/pdf'])
                    ->preserveFilenames()
                    ->columnSpanFull()
                    ->required(),

                // Toggle::make('estado_documento')
                //     ->label('Documento Activo')
                //     ->default(true),

                Select::make('seccion_id')
                    ->relationship('seccion', 'nombre_seccion')
                    ->label('Sección')
                    ->searchable()
                    ->required(),

                Select::make('empleado_id')
                    ->relationship('empleado', 'nombre')
                    ->label('Empleado')
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo_documento')
                    ->label('Título del documento')
                    ->searchable()
                    ->sortable(),

                // TextColumn::make('descripcion_documento')
                //     ->label('Descripción')
                //     ->limit(50)
                //     ->searchable(),

                TextColumn::make('empleado.nombre')
                    ->label('Empleado')
                    ->searchable(),

                TextColumn::make('seccion.nombre_seccion')
                    ->label('Sección')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Fecha de subida')
                    ->dateTime()
                    ->formatStateUsing(fn($state) => $state ? $state->format('d/m/Y H:i:s') : null),

                // SWITCH/TOGGLE para cambiar estado
                ToggleColumn::make('estado_documento')
                    ->label('Estado')
                    ->onColor('success')
                    ->offColor('danger')
                    ->alignCenter()
                    ->extraAttributes([
                        'style' => 'transform: scale(0.5);' // Hace el toggle 20% más pequeño
                    ])
                    ->beforeStateUpdated(function ($record, $state) {
                        // Opcional: validaciones antes de cambiar el estado
                        return $state;
                    })
                    ->afterStateUpdated(function ($record, $state) {
                        // Opcional: mostrar notificación después del cambio
                        \Filament\Notifications\Notification::make()
                            ->title('Estado actualizado')
                            ->body('El documento ha sido ' . ($state ? 'activado' : 'desactivado'))
                            ->success()
                            ->send();
                    }),
            ])
            ->filters([
                SelectFilter::make('seccion_id')
                    ->relationship('seccion', 'nombre_seccion')
                    ->label('Sección')
                    ->multiple()
                    ->placeholder('Seleccionar sección'),

                SelectFilter::make('empleado_id')
                    ->relationship('empleado', 'nombre')
                    ->label('Empleado')
                    ->multiple()
                    ->placeholder('Seleccionar empleado'),

                SelectFilter::make('estado_documento')
                    ->label('Estado')
                    ->options([
                        '1' => 'Activo',
                        '0' => 'Inactivo',
                    ])
                    ->placeholder('Todos los estados'),
            ])
            ->actions([
                Action::make('ver_pdf')
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => Storage::url($record->archivo_ruta))
                    ->openUrlInNewTab()
                    ->color('gray')
                    ->tooltip('Ver PDF'),

                EditAction::make()
                    ->modalHeading('Editar Documento')
                    ->color('warning')
                    ->slideOver()
                    ->modalWidth('2xl')
                    ->label('')
                    ->tooltip('Editar documento'),

                Action::make('Descargar')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn($record) => asset('storage/' . $record->archivo_ruta))
                    ->color('success')
                    ->label('')
                    ->openUrlInNewTab()
                    ->tooltip('Descargar PDF'),

                // Opcional: mantener acción de cambio de estado como respaldo
                Action::make('toggleEstado')
                    ->label('')
                    ->icon(fn($record) => $record->estado_documento ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn($record) => $record->estado_documento ? 'success' : 'danger')
                    ->action(function ($record) {
                        $record->update([
                            'estado_documento' => !$record->estado_documento,
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Estado cambiado')
                            ->body('El documento ha sido ' . ($record->estado_documento ? 'activado' : 'desactivado'))
                            ->success()
                            ->send();
                    })
                    ->tooltip('Cambiar estado')
                    ->hidden(), // Ocultar porque ya tenemos el ToggleColumn
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    // Acciones en lote para cambiar estado
                    Tables\Actions\BulkAction::make('activar_documentos')
                        ->label('Activar seleccionados')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['estado_documento' => true]);
                            });

                            \Filament\Notifications\Notification::make()
                                ->title('Documentos activados')
                                ->body(count($records) . ' documentos han sido activados')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Activar documentos seleccionados')
                        ->modalDescription('¿Estás seguro de que quieres activar todos los documentos seleccionados?'),

                    Tables\Actions\BulkAction::make('desactivar_documentos')
                        ->label('Desactivar seleccionados')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['estado_documento' => false]);
                            });

                            \Filament\Notifications\Notification::make()
                                ->title('Documentos desactivados')
                                ->body(count($records) . ' documentos han sido desactivados')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Desactivar documentos seleccionados')
                        ->modalDescription('¿Estás seguro de que quieres desactivar todos los documentos seleccionados?'),
                ]),
            ])
            ->recordUrl(null);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocumentos::route('/'),
            'agrupados' => Pages\ListGroupedDocumentos::route('/agrupados'),
            'secciones-empleado' => Pages\SeccionesEmpleado::route('/secciones-empleado/{empleado}'),
            'documentos-seccion' => Pages\DocumentosSeccion::route('/documentos-seccion/{empleado}/{seccion}'),
        ];
    }

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make('Documentos Agrupados')
                ->label('Documentos por Sección')
                ->url(static::getUrl('agrupados'))
                ->icon('heroicon-o-folder')
                ->group('Gestión de Documentos'),

            NavigationItem::make(static::getNavigationLabel())
                ->url(static::getUrl('index'))
                ->icon(static::getNavigationIcon())
                ->group(static::getNavigationGroup())
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
