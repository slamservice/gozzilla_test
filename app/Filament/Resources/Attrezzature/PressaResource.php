<?php

namespace App\Filament\Resources\Attrezzature;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Models\Attrezzature\Pressa;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use App\Models\Anagrafiche\Fornitore;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\Attrezzature\PressaResource\Pages;
use App\Filament\Resources\Attrezzature\PressaResource\RelationManagers;

class PressaResource extends Resource
{
    protected static ?string $model = Pressa::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    //**Customitations */
    protected static ?string $recordTitleAttribute = 'codice';

    protected static ?string $slug = 'presse';

    protected static ?string $label = 'pressa';

    protected static ?string $pluralLabel = 'presse';

    protected static ?int $navigationSort = 1;

    protected static function getNavigationGroup(): ?string
    {
        return 'Attrezzature';
    }

    protected static function getNavigationLabel(): string
    {
        return 'Presse';
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
                                ->unique(Pressa::class, 'codice', fn ($record) => $record)
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


                            Forms\Components\TextInput::make('matricola')
                                ->required()
                                ->placeholder('Matricola')
                                ->columnSpan([
                                    'default' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                ]),



                            Forms\Components\BelongsToSelect::make('fornitore_id')
                                ->required()
                                ->relationship('fornitore', 'codiceNome')
                                ->searchable()
                                ->placeholder('Fornitore')
                                ->options(Fornitore::all()->pluck('codiceNome', 'id'))
                                ->getSearchResultsUsing(
                                    fn (string $query) =>
                                    Fornitore::where('codice', 'like', "%{$query}%")
                                        ->orWhere('nome', 'like', "%{$query}%")
                                        ->limit(50)->get()->pluck('codiceNome', 'id')
                                )
                                ->columnSpan([
                                    'default' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                ]),


                        ]),

                    $layout::make()
                        ->schema([
                            Forms\Components\TextInput::make('tonnellaggio')
                                ->required()
                                ->numeric()
                                ->placeholder('Tonnellaggio'),
                            Forms\Components\Grid::make()
                                ->schema([
                                    Forms\Components\TextInput::make('diametro_vite')
                                        ->required()
                                        ->numeric()
                                        ->placeholder('Diametro vite'),

                                    Forms\Components\TextInput::make('grammatura_stampaggio')
                                        ->required()
                                        ->numeric()
                                        ->placeholder('Grammatura Stampaggio'),

                                    Forms\Components\TextInput::make('passaggio_colonne_altezza')
                                        ->required()
                                        ->postfix('X')
                                        ->numeric()
                                        ->placeholder('Passaggio Colonne Altezza'),

                                    Forms\Components\TextInput::make('passaggio_colonne_larghezza')
                                        ->required()
                                        ->numeric()
                                        ->placeholder('Passaggio Colonne Larghezza'),
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
                                ->collection('mediapresse')
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
                Tables\Columns\TextColumn::make('fornitore.codice')->limit(50),
            ])
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\InterventiRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPresse::route('/'),
            'create' => Pages\CreatePressa::route('/create'),
            'edit' => Pages\EditPressa::route('/{record}/edit'),
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
