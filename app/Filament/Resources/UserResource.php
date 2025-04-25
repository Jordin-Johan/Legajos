<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;   
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\CheckboxList;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(255),
                TextInput::make('password')
                    ->required()
                    ->password(),
                CheckboxList::make('roles')
                    ->relationship('roles', 'name')
                    ->label('Roles')
                    ->columns(2)
                    // ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Name'),
                TextColumn::make('email')
                    ->sortable()
                    ->searchable()
                    ->label('Email'),
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->sortable()
                    ->wrap()
                    ->getStateUsing(function ($record) {
                        return $record->roles->pluck('name')->join(', ');
                    }),
                TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime()
                    ->label('Created At'),
                TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime()
                    ->label('Updated At'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
    //codigo para cambier el nombre de la tabla user a español
    public static function getModelLabel(): string
    {
        return 'Usuario';
    }
    
    public static function getPluralModelLabel(): string
    {
        return 'Usuarios';
    }
}
