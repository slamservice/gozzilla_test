<?php

namespace App\Filament\Resources\Anagrafiche;

use Filament\Forms;
use Filament\Tables;
use App\Rules\UniqueLotto;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Models\Anagrafiche\Lotto;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use App\Models\Anagrafiche\Articolo;
use Filament\Forms\Components\Radio;
use App\Models\Anagrafiche\Magazzino;
use App\Models\Anagrafiche\Movimento;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\Anagrafiche\MovimentoResource\Pages;
use App\Filament\Resources\Anagrafiche\MovimentoResource\RelationManagers;

class MovimentoResource extends Resource
{
    protected static ?string $model = Movimento::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $slug = 'movimenti';

    protected static ?string $label = 'movimento';

    protected static ?string $pluralLabel = 'Movimenti';

    protected static ?int $navigationSort = 1;

    protected static function getNavigationGroup(): ?string
    {
        return 'Magazzino';
    }

    protected static function getNavigationLabel(): string
    {
        return 'Movimenti';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->columns(2)
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Radio::make('tipo')
                                ->Label('Tipo Movimento')
                                ->inline()
                                ->required()
                                ->options(Movimento::getTipoMovimento())
                                ->reactive()
                                ->columnSpan(1),
                                Placeholder::make('tipo_movimento')
                                ->disableLabel()
                                ->content(function ($record)
                                {
                                    if (isset($record->tipo_movimento)) {
                                        return $record->tipo_movimento;
                                    }
                                })
                                ->columnSpan(1),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Select::make('magazzino_id')
                                ->label('Magazzino')
                                ->options(Magazzino::all()->pluck('codiceDescrizione', 'id'))
                                ->reactive()
                                ->required()
                                ->searchable()
                                ->columnSpan(1)
                            ]),

                        Select::make('articolo_id')
                            ->label('Articolo')
                            ->options(Articolo::all()->pluck('codiceDescrizione', 'id'))
                            ->reactive()
                            ->required()
                            ->searchable()
                            ->columnSpan(1),

                        //** LOTTO CARICO*/
                        Select::make('lotto_id')
                            ->columnSpan(1)
                            ->relationship('lotti', 'lotto_dataLotto')
                            ->createOptionForm(
                                function (\Closure $get){
                                    $risultato = [];
                                    switch ($get('tipo')) {
                                        case Movimento::CARICO:
                                            $risultato = [
                                                Select::make('articolo_id')
                                                    ->relationship('articoli', 'codice_descrizione',
                                                        fn (Builder $query, \Closure $get) => $query->where('id','=',$get('data.articolo_id',true)))
                                                    ->required()
                                                    ->disabled()
                                                    ->disablePlaceholderSelection()
                                                    ->default(fn (\Closure $get) => $get('data.articolo_id',true)),
                                                TextInput::make('lotto')
                                                    ->required()
                                                    ->rules([new UniqueLotto()]),
                                                DatePicker::make('data_lotto')
                                                    ->required(),
                                            ];
                                            break;
                                        case Movimento::SCARICO:
                                            $risultato = [];
                                            break;
                                    }
                                    return $risultato;
                                })
                            ->label('Lotto')
                            //->searchable()
                            ->options(
                                function (\Closure $get) {
                                    $articolo_id = $get('data.articolo_id',true);
                                    $lotti = array();
                                    if ($articolo_id > 0) {
                                        $lotti = Movimento::lottiArticolo($articolo_id);
                                    }
                                    return $lotti;
                                }
                            )
                            ->required(function (\Closure $get){
                                $risultato = false;
                                switch ($get('tipo')) {
                                    case Movimento::CARICO:
                                        $risultato = false;
                                        break;
                                    case Movimento::SCARICO:
                                        $risultato = true;
                                        break;
                                }
                                return $risultato;
                            }),

                        //** qta CARICO/SCARICO */
                        TextInput::make('qta_carico')
                            ->label('Q.tà Carico')
                            ->default(0)
                            ->numeric()
                            ->hidden(fn (\Closure $get) => $get('tipo') == Movimento::CARICO ? false : true)
                            ->required(fn (\Closure $get) => $get('tipo') == Movimento::CARICO ? true : false),
                        TextInput::make('qta_scarico')
                            ->label('Q.tà Scarico')
                            ->default(0)
                            ->numeric()
                            ->hidden(fn (\Closure $get) => $get('tipo') == Movimento::SCARICO ? false : true)
                            ->required(fn (\Closure $get) => $get('tipo') == Movimento::SCARICO ? true : false),

                        TextInput::make('descrizione')
                            ->label('Descrizione')
                            ->columnSpan(2),

                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('magazzino.codice')->limit(20)
                    ->tooltip(fn (Model $record): string => "{$record->magazzino->descrizione}"),
                Tables\Columns\TextColumn::make('articolo.codice')->limit(20)
                    ->tooltip(fn (Model $record): string => "{$record->articolo->descrizione}"),
                Tables\Columns\TextColumn::make('lotto.lotto')->limit(20),
                Tables\Columns\TextColumn::make('lotto.data_lotto')->date('d/m/Y'),
                Tables\Columns\TextColumn::make('tipo'),
                BadgeColumn::make('tipo')
                    ->enum(Movimento::getTipoMovimento())
                    ->colors(Movimento::getColoriTipoMovimento()),
                Tables\Columns\TextColumn::make('qta_carico')->label('Q.tà Carico'),
                Tables\Columns\TextColumn::make('qta_scarico')->label('Q.tà Scarico'),
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
                    ])
                ->query(function (Builder $query, array $data): Builder {
                    //dump($data);
                return $query
                ->when(
                    $data['magazzino_id'],
                    fn (Builder $query, $date): Builder => $query->where('magazzino_id', '=', $date),
                )
                ->when(
                    $data['articolo_id'],
                    fn (Builder $query, $date): Builder => $query->where('articolo_id', '=', $date),
                );
                })
            ]);
    }

    // public static function Unique_Lotto($get)
    // {
    //     if (is_null($get('mountedFormComponentActionData.lotto',true)) and is_null($get('mountedFormComponentActionData.data_lotto',true))) {
    //         //dd($get('mountedFormComponentActionData.lotto',true));
    //     } else {
    //         //cerca se esiste già il lotto
    //         $lotto = Lotto::select(DB::raw("id as id"))
    //         ->where([
    //             ['lotti.articolo_id','=',$get('mountedFormComponentActionData.articolo_id',true)],
    //             ['lotti.lotto','=',$get('mountedFormComponentActionData.lotto',true)],
    //             ['lotti.data_lotto','=',$get('mountedFormComponentActionData.data_lotto',true)]
    //         ])
    //         ->get()->pluck('lotto', 'id');
    //         dd($lotto);
    //         $pippo = function (string $attribute, $value, \Closure $fail) {
    //             if ($value === 'foo') {
    //                 $fail("The {$attribute} is invalid.");
    //             }
    //         };
    //         dd($pippo);
    //         return $pippo;
    //     }

    //     // $modal = $get('mountedActionData',true);
    //     // dd($modal);
    //     // dd($get('articolo_id',true),$get('lotto',true),$get('data_lotto',true));
    //     //     return function (string $attribute, $value, \Closure $fail) {
    //     //         if ($value === 'foo') {
    //     //             $fail("The {$attribute} is invalid.");
    //     //         }
    //     //     };
    // }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMovimento::route('/'),
            'create' => Pages\CreateMovimento::route('/create'),
            'edit' => Pages\EditMovimento::route('/{record}/edit'),
        ];
    }
}
