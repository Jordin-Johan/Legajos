<?php

namespace App\Filament\Jefatura\Resources;

use App\Filament\Jefatura\Resources\EmpleadoResource\Pages;
use App\Filament\Jefatura\Resources\EmpleadoResource\RelationManagers;
use App\Models\Empleado;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmpleadoResource extends Resource
{
    protected static ?string $model = Empleado::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Grid::make(2)->schema([ // Organiza en 2 columnas
                    FileUpload::make('image')
                        ->label('Foto del empleado')
                        ->image()
                        ->directory('empleados')
                        // ->imagePreviewHeight('30')
                        // ->imageCropAspectRatio('1:1')
                        // ->panelAspectRatio('1:1')
                        ->visibility('public')
                        ->required(),
    
                    TextInput::make('nombre')
                        ->required()
                        ->maxLength(100),
    
                    TextInput::make('apellido')
                        ->required()
                        ->maxLength(100),
    
                    TextInput::make('dni')
                        ->label('DNI')
                        ->required()
                        ->numeric()
                        ->minLength(8)
                        ->maxLength(8)
                        ->extraAttributes(['maxlength' => 8])
                        ->unique(ignoreRecord: true),

                    TextInput::make('direccion')
                        ->maxLength(250),
    
                    TextInput::make('correo')
                        ->email()
                        ->maxLength(250),
    
                    TextInput::make('cargo')
                        ->maxLength(100),
    
                    TextInput::make('varEnlace')->label('Enlace Web')
                        ->helperText('Ej: https://www.ejemplo.com')
                        ->nullable(),
        
                    Select::make('tipoPersonal')
                    ->label('Tipo de Personal')                                         
                        ->options([
                            1 => 'Activo',
                            0 => 'Cesante',
                        ])
                        ->required(),

                    Select::make('tipoContrado')
                    ->label('Tipo de Contrato')
                    ->options([
                        1 => 'CAS',
                        2 => 'Locación de Servicios',
                        3 => 'Nombrado',
                    ])
                    ->required()
                    ->native(false), // Opcional: para un selector estilizado               
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            //
                ImageColumn::make('image')
                    ->label('Foto')
                    // ->circular()
                    ->url(fn($record) => Storage::url($record->image))
                    ->disk('public'), // Asegura que busque en el disco correcto,

                TextColumn::make('nombre')
                    ->label('Nombres')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('apellido')
                    ->label('Apellidos')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('dni')
                    ->label('DNI')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('cargo')
                    ->label('Cargo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tipoPersonal')
                    ->label('Tipo de Personal')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state == 1 ? 'Activo' : 'Cesante')
                    ->badge()
                    
                    ->color(fn ($state) => $state == 1 ? 'success' : 'danger'),

                BadgeColumn::make('tipoContrado')
                    ->label('Tipo de Contrato')
                    ->enum([
                        1 => 'CAS',
                        2 => 'Locación de Servicios',
                        3 => 'Nombrado',
                    ])
                    ->colors([
                        1 => 'warning',
                        2 => 'info',
                        3 => 'success',
                    ]),
                     
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListEmpleados::route('/'),
            'create' => Pages\CreateEmpleado::route('/create'),
            'edit' => Pages\EditEmpleado::route('/{record}/edit'),
        ];
    }
}
