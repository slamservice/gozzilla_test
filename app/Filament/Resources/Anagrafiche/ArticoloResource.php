<?php

namespace App\Filament\Resources\Anagrafiche;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use PhpParser\Node\Expr\Closure;
use App\Models\Anagrafiche\Cliente;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use App\Models\Anagrafiche\Articolo;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Anagrafiche\FamigliaPolimero;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\Anagrafiche\ArticoloResource\Pages;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use App\Filament\Resources\Anagrafiche\ArticoloResource\RelationManagers;
use App\Filament\Resources\Anagrafiche\ArticoloResource\RelationManagers\MovimentiRelationManager;

class ArticoloResource extends Resource
{
    protected static ?string $model = Articolo::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    //**Customitations */
    protected static ?string $recordTitleAttribute = 'codice';

    protected static ?string $slug = 'articoli';

    protected static ?string $label = 'articolo';

    protected static ?string $pluralLabel = 'articoli';

    protected static ?int $navigationSort = 4;

    protected static function getNavigationGroup(): ?string
    {
        return 'Anagrafiche';
    }

    protected static function getNavigationLabel(): string
    {
        return 'Articoli';
    }

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
                            Forms\Components\TextInput::make('codice')
                                ->required()
                                ->placeholder('Codice')
                                ->unique(Articolo::class, 'codice', fn ($record) => $record)
                                ->columnSpan([
                                    'default' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                ]),

                            Forms\Components\TextInput::make('descrizione')
                                ->required()
                                ->placeholder('Descrizione')
                                ->columnSpan([
                                    'default' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                ]),


                            Forms\Components\BelongsToSelect::make('cliente_id')
                                ->relationship('cliente', 'codiceNome')
                                ->searchable()
                                ->placeholder('Cliente')
                                ->options(Cliente::all()->pluck('codiceNome', 'id'))
                                ->getSearchResultsUsing(
                                    fn (string $query) =>
                                    Cliente::where('codice', 'like', "%{$query}%")
                                        ->orWhere('nome', 'like', "%{$query}%")
                                        ->limit(50)->get()->pluck('codiceNome', 'id')
                                )
                                ->columnSpan([
                                    'default' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                ]),
                                Forms\Components\TextInput::make('prezzo_medio')
                                ->placeholder('Prezzo Medio')
                                ->numeric()
                                ->columnSpan([
                                    'default' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                ]),



                        ]),

                    $layout::make()
                        ->schema([
                            Forms\Components\Radio::make('tipologia')
                                ->label('Tipologia')
                                ->inline()
                                ->required()
                                ->reactive()
                                ->options([
                                    'polimero' => 'Polimero',
                                    'master' => 'Master',
                                    'macinato' => 'Macinato',
                                    'imballo' => 'Imballo',
                                    'pezzo_stampato' => 'Pezzo stampato',
                                    'inserto' => 'Inserto'
                                ])
                                ->columnSpan([
                                    'default' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                ]),

                            Forms\Components\TextInput::make('colore_master')
                                ->label('Colore')
                                ->hidden(fn (\Closure $get) => $get('tipologia') !== 'master')
                                ->required(function () {
                                    return  fn (\Closure $get) => $get('tipologia') !== 'master';
                                })
                                ->columnSpan([
                                    'default' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                ]),

                                Forms\Components\BelongsToSelect::make('famiglia_polimero_id')
                                ->relationship('FamigliaPolimero', 'siglaDescrizione')
                                ->searchable()
                                ->placeholder('Famiglia Polimero')
                                ->hidden(
                                    fn (\Closure $get) =>
                                    $get('tipologia') !== 'polimero' ?
                                    ($get('tipologia') !== 'macinato' ?
                                    true
                                    : false
                                    )
                                    : false
                                )
                                ->required(
                                    fn (\Closure $get) =>
                                    $get('tipologia') !== 'polimero' ?
                                    ($get('tipologia') !== 'macinato' ?
                                    false
                                    : true
                                    )
                                    : true
                                )
                                ->options(FamigliaPolimero::all()->pluck('siglaDescrizione', 'id'))
                                ->getSearchResultsUsing(
                                    fn (string $query) =>
                                    Cliente::where('sigla', 'like', "%{$query}%")
                                        ->orWhere('descrizione', 'like', "%{$query}%")
                                        ->limit(50)->get()->pluck('siglaDescrizione', 'id')
                                )
                                ->columnSpan([
                                    'default' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                ]),

                            Forms\Components\Toggle::make('condizionato')
                                ->label('Condizionato')
                                ->default(false)
                                ->reactive()
                                ->hidden(fn (\Closure $get) => $get('tipologia') !== 'polimero')
                                ->columnSpan([
                                    'default' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                ]),


                            Grid::make([
                                'default' => 12,
                            ])
                                ->schema([
                                    Forms\Components\TextInput::make('condizionamento_temperatura')
                                        ->label('Condizionamento Temperatura')
                                        ->integer()
                                        ->hidden(
                                            fn (\Closure $get) =>
                                            $get('tipologia') !== 'polimero' ? true : $get('condizionato') !== true
                                        )
                                        ->required(function () {
                                            return    fn (\Closure $get) =>
                                            $get('tipologia') !== 'polimero' ? true : $get('condizionato') !== true;
                                        })
                                        ->columnSpan([
                                            'default' => 6,
                                            'md' => 6,
                                            'lg' => 6,
                                        ]),

                                    Forms\Components\TextInput::make('condizionamento_tempo')
                                        ->label('Condizionamento Tempo')
                                        ->integer()
                                        ->hidden(
                                            fn (\Closure $get) =>
                                            $get('tipologia') !== 'polimero' ? true : $get('condizionato') !== true
                                        )
                                        ->required(function () {
                                            return fn (\Closure $get) =>
                                            $get('tipologia') !== 'polimero' ? true : $get('condizionato') !== true;
                                        })
                                        ->columnSpan([
                                            'default' => 6,
                                            'md' => 6,
                                            'lg' => 6,
                                        ]),
                                ])->columnSpan([
                                    'default' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                ]),
                        ])
                        ->columns(1),
                    $layout::make()
                        ->schema([
                            TinyEditor::make('nota')
                                ->language('it_IT')
                                ->columnSpan([
                                    'default' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                ]),
                        ])
                        ->columns(1),
                ])->columnSpan([
                    'sm' => 2,
                ]),

            Forms\Components\Group::make()
                ->schema([
                    $layout::make()
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('media')
                                ->collection('mediaarticoli')
                                ->imagePreviewHeight('100')
                                ->multiple()
                                ->enableReordering(),
                        ])
                        ->columns(1),
                ])
                ->columnSpan(1),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codice')->limit(50),
                Tables\Columns\TextColumn::make('descrizione')->limit(50),
                Tables\Columns\TextColumn::make('cliente.codiceNome'),
            ])
            ->filters([
                MultiSelectFilter::make('cliente')->relationship('cliente', 'codice')
            ]);
    }


    // public static function getRelations(): array
    // {
    //     return [
    //         MovimentiRelationManager::class,
    //     ];
    // }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticoli::route('/'),
            'create' => Pages\CreateArticolo::route('/create'),
            'edit' => Pages\EditArticolo::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['codice', 'descrizione'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Codice' => $record->codice,
            'Descrizione' => $record->descrizione,
        ];
    }
}
