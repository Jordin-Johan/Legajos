<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpleadoResource\Pages;
use App\Filament\Resources\EmpleadoResource\RelationManagers;
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
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class EmpleadoResource extends Resource
{
    protected static ?string $model = Empleado::class;

    protected static ?string $navigationGroup = 'Gestión de Personal';
    protected static ?string $navigationIcon = 'heroicon-o-identification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Grid::make(2)->schema([
                    FileUpload::make('image')
                        ->label('Foto del empleado')
                        ->image()
                        ->preserveFilenames()
                        ->directory('empleados')
                        ->imageEditor()
                        ->helperText('Formato: JPG, PNG, JPEG')
                        ->visibility('public')
                        ->columnSpanFull(),

                ]),
                TextInput::make('nombre')
                    ->label('Nombres')
                    ->required()
                    ->maxLength(100)
                    ->suffixIcon('heroicon-o-user'),

                TextInput::make('apellido')
                    ->label('Apellidos')
                    ->required()
                    ->maxLength(100)
                    ->suffixIcon('heroicon-o-user-circle'),

                Grid::make(2)->schema([
                    TextInput::make('dni')
                        ->label('DNI')
                        ->required()
                        ->numeric()
                        ->minLength(8)
                        ->maxLength(8)
                        ->extraAttributes(['maxlength' => 8])
                        ->unique(ignoreRecord: true)
                        ->columnSpanFull()
                        ->suffixIcon('heroicon-o-identification'),

                ]),

                TextInput::make('direccion')
                    ->maxLength(250)
                    ->suffixIcon('heroicon-o-map-pin'),

                TextInput::make('correo')
                    ->email()
                    ->maxLength(250)
                    ->suffixIcon('heroicon-o-at-symbol')
                    ->unique(ignoreRecord: true),


                TextInput::make('cargo')
                    ->maxLength(100)
                    ->suffixIcon('heroicon-o-briefcase'),

                TextInput::make('varEnlace')
                    ->label('Enlace Web')
                    ->placeholder('https://www.ejemplo.com')
                    ->nullable()
                    ->suffixIcon('heroicon-o-link'),

                Select::make('tipoPersonal')
                    ->label('Tipo de Personal')
                    ->options([
                        1 => 'Activo',
                        0 => 'Cesante',
                    ])
                    ->required()
                    ->native(false),

                Select::make('tipoContratado')
                    ->label('Tipo de Contrato')
                    ->options([
                        1 => 'CAS',
                        2 => 'Locación',
                        3 => 'Nombrado',
                        4 => 'Practicante',
                    ])
                    ->required()
                    ->native(false), // Opcional: para un selector estilizado                

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                ImageColumn::make('image')
                    ->label('Foto')
                    // ->preserveFilenames()
                    ->rounded()
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
                    ->formatStateUsing(fn($state) => $state == 1 ? 'Activo' : 'Cesante')
                    ->badge()

                    ->color(fn($state) => $state == 1 ? 'success' : 'danger'),


                BadgeColumn::make('tipoContratado')
                    ->label('Tipo de Contrato')
                    ->colors([
                        'primary' => fn($state) => $state == 1,
                        'info' => fn($state) => $state == 2,
                        'success' => fn($state) => $state == 3,
                        'gray' => fn($state) => $state == 4,
                    ])
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            1 => 'CAS',
                            2 => 'Locación',
                            3 => 'Nombrado',
                            4 => 'Practicante',
                            default => 'Desconocido',
                        };
                    }),

            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Editar Usuario')
                    ->color('warning')
                    ->slideOver()
                    ->modalWidth('2xl')
                    ->label(''),

                Tables\Actions\DeleteAction::make()
                    ->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(null);
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
            // 'create' => Pages\CreateEmpleado::route('/create'),
            // 'edit' => Pages\EditEmpleado::route('/{record}/edit'),
        ];
    }
}
