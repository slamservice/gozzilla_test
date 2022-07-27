<?php

namespace App\Filament\Resources\Anagrafiche;

use App\Filament\Resources\Anagrafiche\FamigliaPolimeroResource\Pages;
use App\Filament\Resources\Anagrafiche\FamigliaPolimeroResource\RelationManagers;
use App\Models\Anagrafiche\FamigliaPolimero;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;

class FamigliaPolimeroResource extends Resource
{
    //protected static bool $shouldRegisterNavigation = false;

    protected static ?string $model = FamigliaPolimero::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    //**Customitations */
    protected static ?string $recordTitleAttribute = 'sigla';

    protected static ?string $slug = 'famiglie_polimero';

    protected static ?string $label = 'famiglia polimero';

    protected static ?string $pluralLabel = 'famiglie polimero';

    protected static ?int $navigationSort = 7;

    protected static function getNavigationGroup(): ?string
    {
        return 'Anagrafiche';
    }

    protected static function getNavigationLabel(): string
    {
        return 'Famiglie Polimeri';
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
                                Forms\Components\TextInput::make('sigla')
                                    ->required()
                                    ->unique(FamigliaPolimero::class, 'sigla', fn ($record) => $record)
                                    ->placeholder('Sigla')
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
                Tables\Columns\TextColumn::make('sigla')->limit(50),
                Tables\Columns\TextColumn::make('descrizione')->limit(50),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFamigliePolimero::route('/'),
            'create' => Pages\CreateFamigliaPolimero::route('/create'),
            'edit' => Pages\EditFamigliaPolimero::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['sigla', 'descrizione'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Sigla' => $record->sigla,
            'Descrizione' => $record->descrizione,
        ];
    }
}
