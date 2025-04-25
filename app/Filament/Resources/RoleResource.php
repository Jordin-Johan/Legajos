<?php

namespace App\Filament\Resources;

use Spatie\Permission\Models\Role;
use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Columns\TextColumn;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('name')
                ->required()
                ->label('Nombre del Rol'),

            CheckboxList::make('permissions')
                ->relationship('permissions', 'name')
                ->label('Permisos')
                ->columns(2),
            ])
            ->recordUrl(null);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nombre del Rol'),
                TextColumn::make('permissions_count')
                    ->label('Cantidad de Permisos')
                    ->getStateUsing(function ($record) {
                        return $record->permissions->count();
                    }),
                TextColumn::make('users_count')
                    ->label('Cantidad de Usuarios')
                    ->getStateUsing(function ($record) {
                        return $record->users->count();
                    }),
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

    public static function query(): Builder
    {
        return parent::query()->with(['permissions', 'users']);
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
