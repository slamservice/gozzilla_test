<?php

namespace App\Filament\Resources\Anagrafiche;


use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Models\Anagrafiche\Cliente;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\Anagrafiche\ClienteResource\Pages;
use App\Filament\Resources\Anagrafiche\ClienteResource\RelationManagers;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'codice';

    protected static ?string $slug = 'clienti';

    protected static ?string $label = 'cliente';

    protected static ?string $pluralLabel = 'clienti';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::getFormSchema(Forms\Components\Card::class))
            ->columns([
                'sm' => 3,
                'lg' => null,
            ]);
    }

    public static function getFormSchema(string $layout = Forms\Components\Grid::class): array
    {
        return [
            Forms\Components\Group::make()
                ->schema([
                    $layout::make()
                        ->schema([
                            Forms\Components\Grid::make(['default' => 0])->schema([
                                Forms\Components\TextInput::make('codice')
                                    ->required()
                                    ->unique(Cliente::class, 'codice', fn ($record) => $record)
                                    ->placeholder('Codice')
                                    ->columnSpan([
                                        'default' => 12,
                                        'md' => 12,
                                        'lg' => 12,
                                    ]),

                                Forms\Components\TextInput::make('nome')
                                    ->rules(['required', 'max:255', 'string'])
                                    ->placeholder('Nome')
                                    ->columnSpan([
                                        'default' => 12,
                                        'md' => 12,
                                        'lg' => 12,
                                    ]),
                            ]),


                        ]),



                ])->columnSpan([
                    'sm' => 2,
                ]),

        ];
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codice')->limit(50),
                Tables\Columns\TextColumn::make('nome')->limit(50),
            ])
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['codice', 'nome'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Codice' => $record->codice,
            'Nome' => $record->nome,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClienti::route('/'),
            'create' => Pages\CreateCliente::route('/create'),
            'edit' => Pages\EditCliente::route('/{record}/edit'),
        ];
    }

    protected static function getNavigationGroup(): ?string
    {
        return 'Anagrafiche';
    }

    protected static function getNavigationLabel(): string
    {
        return 'Clienti';
    }
}
