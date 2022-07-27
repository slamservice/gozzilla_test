<?php

namespace App\Filament\Resources\Anagrafiche;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Models\Anagrafiche\Stampo;
use Filament\Forms\Components\Fieldset;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\Anagrafiche\StampoResource\Pages;
use App\Filament\Resources\Anagrafiche\StampoResource\RelationManagers;

class StampoResource extends Resource
{
    protected static ?string $model = Stampo::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

        //**Customitations */
        protected static ?string $recordTitleAttribute = 'codice';

        protected static ?string $slug = 'stampi';

        protected static ?string $label = 'stampo';

        protected static ?string $pluralLabel = 'stampi';

        protected static ?int $navigationSort = 9;

        protected static function getNavigationGroup(): ?string
        {
            return 'Anagrafiche';
        }

        protected static function getNavigationLabel(): string
        {
            return 'Stampi';
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
                                Forms\Components\Grid::make(['default' => 0])->schema([
                                    Forms\Components\TextInput::make('codice')
                                        ->required()
                                        ->unique(Stampo::class, 'codice', fn ($record) => $record)
                                        ->placeholder('Codice')
                                        ->columnSpan([
                                            'default' => 12,
                                            'md' => 12,
                                            'lg' => 12,
                                        ]),

                                    Forms\Components\TextInput::make('descrizione')
                                        ->rules(['required', 'max:255', 'string'])
                                        ->placeholder('Descrizione')
                                        ->columnSpan([
                                            'default' => 12,
                                            'md' => 12,
                                            'lg' => 12,
                                        ]),

                                        Forms\Components\TextInput::make('ubicazione')
                                        ->rules(['required', 'max:255', 'string'])
                                        ->placeholder('Ubicazione')
                                        ->columnSpan([
                                            'default' => 12,
                                            'md' => 12,
                                            'lg' => 12,
                                        ]),

                                    Forms\Components\Radio::make('tipologia')
                                        ->required()
                                        ->inline()
                                        ->options([
                                            'c3' => 'C/3',
                                            'mtsp' => 'MTSP'
                                        ])
                                        ->columnSpan([
                                            'default' => 12,
                                            'md' => 12,
                                            'lg' => 12,
                                        ]),

                                                Forms\Components\TextInput::make('allestimento')
                                                ->numeric()
                                                ->required()
                                                ->placeholder('Allestimento tempo in minuti')
                                                ->columnSpan([
                                                    'default' => 6,
                                                    'md' => 6,
                                                    'lg' => 6,
                                                ]),

                                            Forms\Components\TextInput::make('disallestimento')
                                                ->required()
                                                ->numeric()
                                                ->placeholder('Disallestimento tempo in minuti')
                                                ->columnSpan([
                                                    'default' => 6,
                                                    'md' => 6,
                                                    'lg' => 6,
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
                Tables\Columns\TextColumn::make('descrizione')->limit(50),
                Tables\Columns\TextColumn::make('tipologia')->limit(50),
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
            'index' => Pages\ListStampi::route('/'),
            'create' => Pages\CreateStampo::route('/create'),
            'edit' => Pages\EditStampo::route('/{record}/edit'),
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
