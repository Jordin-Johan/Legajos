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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class DocumentoResource extends Resource
{
    protected static ?string $model = Documento::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Gestión de Documentos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('titulo_documento')
                    ->label('Título del documento')
                    ->required(),

                    Textarea::make('descripcion_documento')
                    ->required()
                    ->maxLength(250),
    
                FileUpload::make('archivo_ruta')
                    ->label('Archivo PDF')
                    ->disk('public') // Asegúrate de tener el disco 'public' configurado
                    ->directory('documentos')
                    ->acceptedFileTypes(['application/pdf'])
                    ->required(),
    
                Toggle::make('estado_documento')
                    ->label('Activo')
                    ->default(true),   

                Select::make('seccion_id')
                    ->relationship('seccion', 'nombre_seccion')
                    ->searchable()
                    ->required(),
    
                Select::make('empleado_id')
                    ->relationship('empleado', 'nombre') // Asegúrate de tener el atributo `nombre` en tu modelo
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('titulo_documento')
                ->label('Título del documento')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('descripcion_documento')
                    ->label('Descripción')
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('empleado.nombre')
                    ->label('Empleado')
                    ->searchable(),

                TextColumn::make('seccion.nombre_seccion')
                    ->label('Sección')
                    ->searchable(),
                
                BadgeColumn::make('estado_documento')
                    ->label('Estado')
                    ->colors([
                        'success' => fn ($state) => $state === true,
                        'danger' => fn ($state) => $state === false,
                    ])
                    ->formatStateUsing(fn ($state) => $state ? 'Activo' : 'Inactivo'),

                TextColumn::make('created_at')
                    ->label('Fecha de subida')
                    ->dateTime()
                    ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y H:i:s') : null)
                    ->sortable(),               

                IconColumn::make('estado_documento')
                    ->label('Estado')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->color('success')
                    ->sortable()
                    ->toggleable(),               
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('ver_pdf')
                    ->label('Ver PDF')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => Storage::url($record->archivo_ruta))
                    ->openUrlInNewTab()
                    ->color('primary'),
                EditAction::make(),
                Action::make('Descargar')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn ($record) => asset('storage/' . $record->archivo))
                    ->openUrlInNewTab(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocumentos::route('/'),
            'create' => Pages\CreateDocumento::route('/create'),
            'edit' => Pages\EditDocumento::route('/{record}/edit'),
        ];
    }
}
