<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
<<<<<<< HEAD:hr-callcenter-system/app/Filament/Resources/UserResource.php
use App\Models\SubCity;
use App\Models\Tip;
=======
>>>>>>> eda5f637f61aba7a99db1ae1b51ac1ad4e697aba:app/Filament/Resources/UserResource.php
use App\Models\User;
use App\Support\Filament\PanelAccess;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';
    protected static string|\UnitEnum|null $navigationGroup = 'User Management';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return $query;
        }

        if ($user->hasRole('admin')) {
            $subCityId = \App\Helpers\JurisdictionHelper::getSubCityId($user);
            $query->where('sub_city_id', $subCityId);
        } elseif ($user->hasRole('woreda_coordinator')) {
            $woredaId = \App\Helpers\JurisdictionHelper::getWoredaId($user);
            $query->where('woreda_id', $woredaId);
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
<<<<<<< HEAD:hr-callcenter-system/app/Filament/Resources/UserResource.php
                Forms\Components\TextInput::make('username')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Select::make('sub_city')
                    ->label('Assigned Sub City')
                    ->options(fn (): array => SubCity::query()->orderBy('code')->pluck('name_en', 'name_en')->all())
                    ->searchable()
                    ->placeholder('Select sub city for Sub City HR or sub-city/woreda officers')
                    ->nullable()
                    ->live(),
                Forms\Components\Select::make('woreda')
                    ->label('Assigned Woreda')
                    ->options(Tip::getWoredaOptions())
                    ->searchable()
                    ->placeholder('Select woreda for woreda officers')
                    ->nullable()
                    ->visible(fn(Get $get) => filled($get('sub_city'))),
=======
>>>>>>> eda5f637f61aba7a99db1ae1b51ac1ad4e697aba:app/Filament/Resources/UserResource.php
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create'),
                Forms\Components\Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload(),
                Forms\Components\Select::make('sub_city_id')
                    ->relationship('subCity', 'name_am')
                    ->label('Sub-City (ክፍለ ከተማ)')
                    ->live(),
                Forms\Components\Select::make('woreda_id')
                    ->label('Woreda (ወረዳ)')
                    ->options(function (callable $get) {
                        $subCityId = $get('sub_city_id');
                        if ($subCityId) {
                            return \App\Models\Woreda::where('sub_city_id', $subCityId)
                                ->pluck('name_am', 'id');
                        }
                        return [];
                    }),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('subCity.name_am')
                    ->label('Sub-City')
                    ->placeholder('—')
                    ->searchable(),
                Tables\Columns\TextColumn::make('woreda.name_am')
                    ->label('Woreda')
                    ->placeholder('—')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
<<<<<<< HEAD:hr-callcenter-system/app/Filament/Resources/UserResource.php
        return PanelAccess::allows(['manage_users']);
=======
        $user = auth()->user();

        return (bool) $user && (
            $user->hasRole('admin')
            || $user->can('manage_users')
        );
>>>>>>> eda5f637f61aba7a99db1ae1b51ac1ad4e697aba:app/Filament/Resources/UserResource.php
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }
<<<<<<< HEAD:hr-callcenter-system/app/Filament/Resources/UserResource.php

    public static function canCreate(): bool
    {
        return static::canViewAny();
    }

    public static function canEdit($record): bool
    {
        return static::canViewAny();
    }

    public static function canDelete($record): bool
    {
        return static::canViewAny();
    }

    public static function canDeleteAny(): bool
    {
        return static::canViewAny();
    }
=======
>>>>>>> eda5f637f61aba7a99db1ae1b51ac1ad4e697aba:app/Filament/Resources/UserResource.php
}
