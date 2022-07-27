<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Giacenza;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Tables\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Pages\Actions\Action;
use Filament\Forms\Components\Card;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use App\Models\Anagrafiche\Articolo;
use App\Models\Anagrafiche\Magazzino;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\HasManyRepeater;
use Filament\Tables\Filters\MultiSelectFilter;
use App\Filament\Resources\GiacenzaResource\Pages;
use App\Filament\Resources\GiacenzaResource\RelationManagers;
use App\Forms\Components\LottogiacenzaForm;

class GiacenzaResource extends Resource
{
    protected static ?string $model = Giacenza::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';


    protected static ?string $slug = 'giacenza';

    protected static ?string $label = 'giacenza';

    protected static ?string $pluralLabel = 'Giacenza';

    protected static ?int $navigationSort = 2;

    protected static function getNavigationGroup(): ?string
    {
        return 'Magazzino';
    }

    protected static function getNavigationLabel(): string
    {
        return 'Giacenza';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
    //             Card::make()
    // ->schema([
        Repeater::make('lotti')
                ->relationship('lotti')
                    ->schema([
                        Placeholder::make('lotto')
                        ->label('Lotto')
                        ->content(function ($record){
                            return $record->lotto;
                        })
                        ->columnSpan(3),
                        Placeholder::make('data_lotto')
                        ->label('Data Lotto')
                        ->content(function ($record){
                            return date_format($record->data_lotto, "d/m/Y");
                        })
                        ->columnSpan(3),
                        Placeholder::make('qta_carico')
                        ->label('Carico')
                        ->content(function ($record){
                            return $record->qta_carico;
                        })
                        ->columnSpan(2),
                        Placeholder::make('qta_scarico')
                        ->label('Scarico')
                        ->content(function ($record){
                            return $record->qta_scarico;
                        })
                        ->columnSpan(2),
                    ])
            ->columns(12)
            ->columnSpan(12)
            ->disableItemCreation()
            ->disableItemDeletion()
            ->disableItemMovement()
    // ])
    // ->columns(10)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('magazzino')
                //->tooltip(fn (Model $record): string => "{$record->magazzino->descrizione}")
                ->sortable(),
                Tables\Columns\TextColumn::make('articolo')->sortable(),
                Tables\Columns\TextColumn::make('tipologia')
                ->label('Tipologia')->sortable(),
                Tables\Columns\TextColumn::make('giacenza')->sortable(),
            ])
            ->actions([
                ViewAction::make('lotti')
                ->label('Lotti')
                ->modalHeading(function ($record){
                    return $record->articolo;
                })
                ->form(
                    [LottogiacenzaForm::make('lotto'),]
                ),
            ])
            ->filters([
                Filter::make('Magazzino')
                    ->form([
                        Select::make('magazzino_id')
                        ->label('Magazzino')
                        ->options(Magazzino::all()->pluck('codiceDescrizione', 'id'))
                        ->searchable(),
                        Select::make('articolo_id')
                        ->label('Articolo')
                        ->options(Articolo::all()->pluck('codiceDescrizione', 'id'))
                        ->searchable(),
                        Select::make('tipologia')
                        ->label('Tipologia')
                        ->options(Articolo::getTipologieArticolo()),
                    ])
                ->query(function (Builder $query, array $data): Builder {
                    //dump($query);
                return $query
                ->when(
                    $data['magazzino_id'],
                    fn (Builder $query, $date): Builder => $query->where('magazzino_id', '=', $date),
                )
                ->when(
                    $data['articolo_id'],
                    fn (Builder $query, $date): Builder => $query->where('articolo_id', '=', $date),
                )
                ->when(
                    $data['tipologia'],
                    fn (Builder $query, $date): Builder => $query->where('articoli.tipologia', '=', $date),
                );
                })
            ]);
    }

    protected function getTableFiltersLayout(): ?string
{
    return Layout::AboveContent;
}

    protected function getTableFiltersFormColumns(): int
    {
        return 5;
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
            'index' => Pages\ListGiacenza::route('/'),
            'create' => Pages\CreateGiacenza::route('/create'),
            //'view' => Pages\ViewGiacenza::route('/{record}'),
            'edit' => Pages\EditGiacenza::route('/{record}/edit'),
        ];
    }
}
