<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\Storage;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                FileUpload::make('foto')
                    ->label('Foto de perfil')
                    ->image()
                    ->directory('users')
                    ->imageEditor()
                    ->imageCropAspectRatio('1:1')
                    ->preserveFilenames()
                    ->columnSpanFull(),

                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nombre')
                    ->suffixIcon('heroicon-o-user'),

                TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(255)
                    ->label('Correo')
                    ->suffixIcon('heroicon-o-at-symbol'),

                TextInput::make('password')
                    ->required()
                    ->password()
                    ->label('Contraseña')
                    ->suffixIcon('heroicon-o-lock-closed')
                    ->dehydrated(fn($state) => filled($state)) // Solo guarda si se ha escrito algo
                    ->required(fn(string $context) => $context === 'create'), // Obligatoria solo al crear,

                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->label('Rol'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto')
                    // ->preserveFilenames()
                    ->rounded()
                    ->url(fn($record) => Storage::url($record->foto))
                    ->disk('public'),

                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Nombre'),

                TextColumn::make('email')
                    ->sortable()
                    ->searchable()
                    ->label('Correo'),

                TextColumn::make('roles.name')
                    ->sortable()
                    ->searchable()
                    ->label('Rol'),

                TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime('d/m/Y H:i:s')
                    ->label('Fecha Creado'),

                TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime('d/m/Y H:i:s')
                    ->label('Actualizado'),
            ])
            ->filters([
                //
            ])
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
            'index' => Pages\ListUsers::route('/'),
            // 'create' => Pages\CreateUser::route('/create'),
            // 'edit' => Pages\EditUser::route('/{record}/edit'),
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
