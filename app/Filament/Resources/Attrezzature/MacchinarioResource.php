<?php

namespace App\Filament\Resources\Attrezzature;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Models\Anagrafiche\Fornitore;
use Illuminate\Database\Eloquent\Model;
use App\Models\Attrezzature\Macchinario;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\Attrezzature\MacchinarioResource\Pages;
use App\Filament\Resources\Attrezzature\MacchinarioResource\RelationManagers;

class MacchinarioResource extends Resource
{
    protected static ?string $model = Macchinario::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    //**Customitations */
    protected static ?string $recordTitleAttribute = 'codice';

    protected static ?string $slug = 'macchinario';

    protected static ?string $label = 'macchinario';

    protected static ?string $pluralLabel = 'macchinari';

    protected static ?int $navigationSort = 3;

    protected static function getNavigationGroup(): ?string
    {
        return 'Attrezzature';
    }

    protected static function getNavigationLabel(): string
    {
        return 'Macchinari';
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
                                ->unique(Macchinario::class, 'codice', fn ($record) => $record)
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
                                ->placeholder('Matricola')
                                ->columnSpan([
                                    'default' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                ]),



                            Forms\Components\BelongsToSelect::make('fornitore_id')
                                ->relationship('fornitore', 'codiceNome')
                                ->required()
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



                ])->columnSpan([
                    'sm' => 2,
                ]),

            Forms\Components\Group::make()
                ->schema([
                    $layout::make()
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('media')
                                ->collection('mediamacchinari')
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
                Tables\Columns\TextColumn::make('fornitore.codiceNome'),
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
            'index' => Pages\ListMacchinari::route('/'),
            'create' => Pages\CreateMacchinario::route('/create'),
            'edit' => Pages\EditMacchinario::route('/{record}/edit'),
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
