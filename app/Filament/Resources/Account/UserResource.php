<?php

namespace App\Filament\Resources\Account;

use App\Filament\Resources\Account\UserResource\Pages;
use Spatie\Permission\Models\Role;
use App\Filament\Resources\Account\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use STS\FilamentImpersonate\Impersonate;

class UserResource extends Resource
{

    protected static ?string $model = User::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('slamservice.users.field.name'))
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label(__('slamservice.users.field.email'))
                            ->required()
                            ->email()
                            ->maxLength(255)
                            ->unique(User::class, 'email', fn ($record) => $record),
                        Forms\Components\TextInput::make('password')
                            ->label(__('slamservice.users.field.password'))
                            ->password()
                            ->minLength(8)
                            ->maxLength(255)
                            ->required(fn (Component $livewire): bool => $livewire instanceof Pages\CreateUser)
                            ->same('passwordConfirmation')
                            ->helperText(function (Component $livewire) {
                                if ($livewire instanceof Pages\EditUser) {
                                    return __('slamservice.users.notification.password-helper');
                                }
                            }),
                        Forms\Components\TextInput::make('passwordConfirmation')
                            ->label(__('slamservice.users.field.confirm-password'))
                            ->password()
                            ->minLength(8)
                            ->maxLength(255)
                            ->required(fn (Component $livewire): bool => $livewire instanceof Pages\CreateUser)
                            ->dehydrated(false),
                        Forms\Components\BelongsToManyMultiSelect::make('roles')
                            ->label(__('slamservice.users.field.roles'))
                            ->relationship('roles', 'name')
                            ->options(Role::all()->pluck('name', 'id'))
                            ->required()
                    ])
                    ->columns([
                        'sm' => 2,
                    ])
                    ->columnSpan(2),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label(__('slamservice.users.field.created-at'))
                            ->content(fn (?User $record): string => $record ? $record->created_at->diffForHumans() : '-'),
                        Forms\Components\Placeholder::make('updated_at')
                            ->label(__('slamservice.users.field.updated-at'))
                            ->content(fn (?User $record): string => $record ? $record->updated_at->diffForHumans() : '-'),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->rounded(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('slamservice.users.field.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('slamservice.users.field.email'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('roles.name')
                    ->label(__('slamservice.users.field.roles'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ]);
            // ->prependActions([
            //     Impersonate::make('impersonate'),
            // ]);
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
            'create' => Pages\CreateUser::route('/tambah'),
            'edit' => Pages\EditUser::route('/{record}/sunting'),
        ];
    }

    public static function getLabel(): string
    {
        return __('slamservice.users.resource.label');
    }

    public static function getPluralLabel(): string
    {
        return __('slamservice.users.resource.labels');
    }

    protected function getTitle(): string
    {
        return __('slamservice.users.resource.title');
    }

    public static function getSlug(): string
    {
        return __('slamservice.users.resource.slug');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('filament-shield::filament-shield.nav.group');
    }


    protected static function getNavigationLabel(): string
    {
        return __('slamservice.users.resource.nav.label');
    }
}
