<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeccionResource\Pages;
use App\Filament\Resources\SeccionResource\RelationManagers;
use App\Models\Seccion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;

class SeccionResource extends Resource
{
    protected static ?string $model = Seccion::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
    protected static ?string $navigationGroup = 'Gestión de Personal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('nombre_seccion')
                    ->label('Nombre de la sección')
                    ->required()
                    ->maxLength(250)
                    ->helperText('Ej: Recusos Humanos'),
                TextInput::make('descripcion')
                    ->label('Descripción de la sección')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('nombre_seccion')
                    ->label('Nombre de la sección')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('descripcion')
                    ->label('Descripción de la sección')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
                Filter::make('nombre_seccion')
                    ->label('Nombre de la sección')
                    ->query(fn(Builder $query): Builder => $query->where('nombre_seccion', '!=', '')),

            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Editar sección')
                    ->color('warning')
                    ->slideOver()
                    // ->modalWidth('lg')
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
            'index' => Pages\ListSeccions::route('/'),
            // 'create' => Pages\CreateSeccion::route('/create'),
            // 'edit' => Pages\EditSeccion::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'Sección';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Secciones';
    }
}
