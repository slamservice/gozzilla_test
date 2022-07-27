<?php

namespace App\Forms\Components;

use Filament\Forms;
use App\Models\Pfcmadre;

use App\Models\Anagrafiche\Stampo;
use App\Models\Anagrafiche\Cliente;
use App\Models\Attrezzature\Pressa;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use App\Models\Anagrafiche\Articolo;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class PfcpreviewForm extends Forms\Components\Field
{
    protected string $view = 'forms::components.group';

    public function getChildComponents(): array
    {
        return [

            Forms\Components\Card::make()
            ->schema([

                Forms\Components\Grid::make(['default' => 0])->schema([

                    Forms\Components\TextInput::make('codice')
                        ->required()
                        ->disabled()
                        ->unique(Pfcmadre::class, 'codice', fn ($record) => $record)
                        ->placeholder('Codice'),

                    Forms\Components\BelongsToSelect::make('cliente_id')
                        ->relationship('cliente', 'codiceNome')
                        ->required()
                        ->searchable()
                        ->placeholder('Cliente')
                        ->options(Cliente::all()->pluck('codiceNome', 'id'))
                        ->reactive()
                        ->getSearchResultsUsing(
                            fn (string $query) =>
                            Cliente::where('codice', 'like', "%{$query}%")
                                ->orWhere('nome', 'like', "%{$query}%")
                                ->get()->pluck('codiceNome', 'id')
                        ),
                ]),
            ])
            ->columnSpan([
                'sm' => 2,
            ]),
        // Tabs::make('Heading')
        //     ->tabs([
        Card::make()
            ->schema([
                Repeater::make('articoli')
                    ->relationship('articoli')
                    ->registerListeners([
                        'repeater::deleteItem' => [
                            function (Repeater $component) {
                                $codiceok = '';
                                $somma = 0;
                                $container = $component->getLivewire()->data['articoli'];
                                foreach ($container as $row) {
                                    $codeart = Articolo::find($row['articolo_id'])->codice;
                                    $codiceok .= '+' . $codeart;
                                    $somma += ($row['numero_impronte'] * $row['peso_impronte']);
                                }
                                $component->getLivewire()->data['codice'] = substr($codiceok, 1);
                                $somma += $component->getLivewire()->data['peso_matarozza'];
                                $component->getLivewire()->data['peso_stampata'] = $somma;
                            },
                        ],
                    ])
                    ->schema([
                        Forms\Components\Select::make('articolo_id')
                            ->label('Articolo')
                            ->getOptionLabelUsing(fn ($value): ?string => Articolo::find($value)?->codiceDescrizione)
                            //->options(Articolo::all()->pluck('codiceDescrizione', 'id'))
                            //->options(fn($get) => Articolo::where('cliente_id', '=', $get('data.cliente_id', true))->get()->pluck('codiceDescrizione', 'id'))
                            ->reactive()
                            ->required()
                            ->getSearchResultsUsing(
                                fn (string $query, \Closure $get) =>
                                Articolo::where('cliente_id', '=', $get('data.cliente_id', true))
                                    ->where(function ($internalQuery) use ($query) {
                                        $internalQuery->where('codice', 'like', "%{$query}%")
                                            ->orWhere('descrizione', 'like', "%{$query}%");
                                    })
                                    ->get()->pluck('codiceDescrizione', 'id')
                            )
                            ->searchable()
                            ->afterStateUpdated(
                                function ($state, \Closure $get, callable $set) {
                                    //var_dump($get('articolo_id'));
                                    $rows = $get('data.articoli', true);
                                    $codiceok = '';
                                    foreach ($rows as $row) {
                                        if (is_null($row['articolo_id'])) {

                                        } else {
                                            $codeart = Articolo::find($row['articolo_id'])->codice;
                                            $codiceok .= '+' . $codeart;
                                        }
                                    }
                                    $set('data.codice', substr($codiceok, 1), true);
                                }
                            )
                            ->columnSpan([
                                'md' => 5,
                            ]),
                        Forms\Components\TextInput::make('numero_impronte')
                            ->label('Numero impronte')
                            ->numeric()
                            ->reactive()
                            ->afterStateUpdated(
                                function ($state, \Closure $get, callable $set) {
                                    $rows = $get('data.articoli', true);
                                    //dd($rows);
                                    $somma = 0;
                                    foreach ($rows as $row) {
                                        //dd($row['numero_impronte']);
                                        $somma += ($row['numero_impronte'] * $row['peso_impronte']);
                                    }
                                    //dd($get('data.peso_matarozza',true));
                                    $somma += $get('data.peso_matarozza',true);
                                    $set('data.peso_stampata', $somma, true);
                                }
                            )
                            // ->mask(
                            //     fn (Forms\Components\TextInput\Mask $mask) => $mask
                            //         ->numeric()
                            //         ->integer()
                            //         ->thousandsSeparator('.')
                            // )
                            ->default(0)
                            // ->disabled(
                            //     function (\Closure $get) {
                            //         //var_dump($get('cliente_id'));
                            //         ($get('cliente_id') <= 0) ? true : false;
                            //     }
                            // )
                            ->columnSpan([
                                'md' => 2,
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('peso_impronte')
                            ->label('Peso singola impronta (in grammi)')
                            ->numeric()
                            ->reactive()
                            ->afterStateUpdated(
                                function ($state, \Closure $get, callable $set) {
                                    $rows = $get('data.articoli', true);
                                    //dd($rows);
                                    $somma = 0;
                                    foreach ($rows as $row) {
                                        //dd($row['numero_impronte']);
                                        $somma += ($row['numero_impronte'] * $row['peso_impronte']);
                                    }
                                    $somma += $get('data.peso_matarozza',true);
                                    $set('data.peso_stampata', $somma, true);
                                }
                            )
                            // ->mask(
                            //     fn (Forms\Components\TextInput\Mask $mask) => $mask
                            //         ->numeric()
                            //         ->decimalPlaces(2)
                            //         ->thousandsSeparator('.')
                            //         ->decimalSeparator(',')
                            // )
                            ->required()
                            //->disabled(fn (\Closure $get) => $get('cliente_id') <= 0)
                            ->columnSpan([
                                'md' => 3,
                            ]),
                    ])
                    ->defaultItems(1)
                    ->label('Selezionare prima un cliente per caricare solo i suoi articoli.')
                    ->createItemButtonLabel('Aggiungi articolo')
                    ->disableLabel(fn (\Closure $get) => $get('cliente_id') > 0)
                    ->columns([
                        'md' => 10,
                    ])
                    // ->disabled(fn (\Closure $get) => ($get('cliente_id') <= 0) ? true : false)
                    ->required(),
            ]),

            Card::make()
            ->schema([
                Repeater::make('imballi')
                    ->relationship('imballi')
                    ->schema([
                        Forms\Components\Select::make('articolo_imballo_id')
                            ->label('Imballo')
                            //->relationship('articoli', 'codiceDescrizione')
                            ->options(
                                Articolo::where('tipologia', '=', Articolo::IMBALLO)
                                    ->get()->pluck('codiceDescrizione', 'id')
                            )
                            ->required()
                            // ->reactive()
                            ->getSearchResultsUsing(
                                fn (string $query) =>
                                Articolo::where('tipologia', '=', Articolo::IMBALLO)
                                    ->where(function ($internalQuery) use ($query) {
                                        $internalQuery->where('codice', 'like', "%{$query}%")
                                            ->orWhere('descrizione', 'like', "%{$query}%");
                                    })
                                    ->get()->pluck('codiceDescrizione', 'id')
                            )
                            ->searchable()
                            //->reactive()
                            //->afterStateUpdated(fn ($state, callable $set) => $set('unit_price', Product::find($state)?->price ?? 0))
                            ->columnSpan(3),
                        Forms\Components\TextInput::make('nr_conf_per_scatola')
                            ->label('Nr.Conf. per scatola')
                            ->required()
                            ->numeric()
                            ->mask(
                                fn (Forms\Components\TextInput\Mask $mask) => $mask
                                    ->numeric()
                                    ->thousandsSeparator('.')
                            )
                            //->required()
                            ->columnSpan(2),
                            Forms\Components\Select::make('articolo_id')
                            ->label('Articolo')
                            ->options(
                                function ($component) {
                                    $codearts = array();
                                    $container = $component->getLivewire()->data['articoli'];
                                    //dd($container);
                                foreach ($container as $row) {
                                    //$codearts .= $row['articolo_id'].',';
                                    array_push($codearts,$row['articolo_id']);
                                    //dump($codearts);
                                    // $codiceok .= '+' . $codeart;
                                    // $somma += ($row['numero_impronte'] * $row['peso_impronte']);
                                }
                                //dd($codearts);
                                $test = Articolo::wherein('id', $codearts)
                                ->get()->pluck('codiceDescrizione', 'id');
                                return $test;
                                }
                            )
                            ->columnSpan(3),
                    ])
                    ->dehydrated()
                    ->defaultItems(0)
                    ->createItemButtonLabel('Aggiungi imballo')
                    ->label('Imballi')
                    ->orderable()
                    //->disableLabel(fn (\Closure $get) => $get('cliente_id') > 0)
                    ->columns(8)
                //->required()
                //->disabled(fn (\Closure $get) => $get('cliente_id') <= 0),
            ]),

        Card::make()
            ->schema([
                Forms\Components\BelongsToSelect::make('stampo_id')
                    ->relationship('stampo', 'codiceDescrizione')
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(
                        function ($state, \Closure $get, callable $set) {
                            //var_dump($get('articolo_id'));
                            $ubicazione = Stampo::find($get('stampo_id'))->ubicazione;
                            //dd($ubicazione);
                            $set('data.stampo_ubicazione', $ubicazione, true);
                        }
                    )
                    ->options(Stampo::all()->pluck('codiceDescrizione', 'id'))
                    ->getSearchResultsUsing(
                        fn (string $query) =>
                        Stampo::where('codice', 'like', "%{$query}%")
                            ->orWhere('descrizione', 'like', "%{$query}%")
                            ->get()->pluck('codiceDescrizione', 'id')
                    )
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),
                Forms\Components\TextInput::make('stampo_ubicazione')
                    ->required()
                    ->disabled()
                    ->label('Ubicazione')
                    //->placeholder('Ubicazione')
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),


                Forms\Components\Toggle::make('stampo_condizionamento')
                    ->label('Condizionamento')
                    ->reactive()
                    ->columnSpan([
                        'default' => 8,
                        'md' => 8,
                        'lg' => 8,
                    ]),
                //*****************  */
                Grid::make(['default' => 1])
                    ->schema([
                        Forms\Components\Radio::make('stampo_tipo_condizionamento')
                            ->Label('Tipo condizionamento')
                            ->inline()
                            ->required(fn (\Closure $get) => $get('stampo_condizionamento'))
                            ->hidden(fn (\Closure $get) => $get('stampo_condizionamento') == true ? false : true)
                            ->options(Stampo::getTipoCondizionamentoStampo())
                            ->afterStateUpdated(
                                function ($state, callable $set, \Closure $get) {
                                    $result = false;
                                    if ($get('stampo_condizionamento') == 1) {
                                        $result = ($get('stampo_tipo_condizionamento') == Stampo::FISSOeMOBILE) ? $set('stampo_numero_linee', 1) : $set('stampo_numero_linee', '');
                                    }
                                    return $result;
                                }
                            )
                            ->reactive()
                            ->columnSpan([
                                'default' => 4,
                                'md' => 4,
                                'lg' => 4,
                            ]),
                        Forms\Components\Radio::make('stampo_subtipo_condizionamento')
                            ->label('Subtipo condizionamento')
                            ->afterStateUpdated(
                                function ($state, callable $set, \Closure $get) {
                                    $result = false;
                                    if ($get('stampo_condizionamento') == 1) {
                                        $result = ($get('stampo_tipo_condizionamento') == Stampo::FISSOeMOBILE) ? $set('stampo_numero_linee', 1) : $set('stampo_numero_linee', '');
                                    }
                                    return $result;
                                }
                            )
                            ->inline()
                            ->required(fn (\Closure $get) => $get('stampo_condizionamento'))
                            ->hidden(fn (\Closure $get) => $get('stampo_condizionamento') == true ? false : true)
                            ->options(Stampo::getSubTipoCondizionamentoStampo())
                            ->reactive()
                            ->columnSpan([
                                'default' => 12,
                                'md' => 12,
                                'lg' => 12,
                            ]),
                        Forms\Components\TextInput::make('stampo_numero_linee')
                            ->label('Numero linee')
                            ->disabled(
                                function ($state, callable $set, \Closure $get) {
                                    $result = false;
                                    if ($get('stampo_condizionamento') == 1) {
                                        $result = ($get('stampo_tipo_condizionamento') == Stampo::FISSOeMOBILE) ? $result = true : $result = false;
                                    }
                                    return $result;
                                }
                            )
                            ->required(
                                fn (\Closure $get) => Stampo::HiddenRequired_numero_linee(
                                    'required',
                                    $get('stampo_condizionamento'),
                                    $get('stampo_subtipo_condizionamento')
                                )
                            )
                            ->numeric()
                            ->hidden(
                                fn (\Closure $get) => Stampo::HiddenRequired_numero_linee(
                                    'hidden',
                                    $get('stampo_condizionamento'),
                                    $get('stampo_subtipo_condizionamento')
                                )
                            )
                            ->columnSpan('full'),
                        Forms\Components\TextInput::make('stampo_temperatura')
                            ->label('Temperatura')
                            ->required(
                                fn (\Closure $get) => Stampo::HiddenRequired_temperatura(
                                    'required',
                                    $get('stampo_condizionamento'),
                                    $get('stampo_subtipo_condizionamento')
                                )
                            )
                            ->numeric()
                            ->hidden(
                                fn (\Closure $get) => Stampo::HiddenRequired_temperatura(
                                    'hidden',
                                    $get('stampo_condizionamento'),
                                    $get('stampo_subtipo_condizionamento')
                                )
                            )
                            ->columnSpan('full'),

                        //*** FISSO/MOBILE **/
                        Forms\Components\Radio::make('stampo_subtipo_condizionamento_fm')
                            ->label('Subtipo condizionamento f/m')
                            ->inline()
                            ->required(
                                function (\Closure $get) {
                                    $result = false;
                                    if ($get('stampo_condizionamento') == 1) {
                                        $result = ($get('stampo_tipo_condizionamento') == Stampo::FISSOoMOBILE) ? true : false;
                                    }
                                    //var_dump($result);
                                    return $result;
                                }
                            )
                            ->hidden(
                                function (\Closure $get) {
                                    if ($get('stampo_condizionamento') == 1) {
                                        $result = ($get('stampo_tipo_condizionamento') == Stampo::FISSOoMOBILE) ? false : true;
                                    } else {
                                        $result = true;
                                    }
                                    return $result;
                                }
                            )
                            ->options(Stampo::getSubTipoCondizionamentoStampo())
                            ->reactive()
                            ->columnSpan([
                                'default' => 12,
                                'md' => 12,
                                'lg' => 12,
                            ]),
                        Forms\Components\TextInput::make('stampo_numero_linee_fm')
                            ->label('Numero linee f/m')
                            ->required(
                                fn (\Closure $get) => Stampo::HiddenRequired_numero_linee_fm(
                                    'required',
                                    $get('stampo_condizionamento'),
                                    $get('stampo_tipo_condizionamento'),
                                    $get('stampo_subtipo_condizionamento_fm')
                                )
                            )
                            ->numeric()
                            ->hidden(
                                fn (\Closure $get) => Stampo::HiddenRequired_numero_linee_fm(
                                    'hidden',
                                    $get('stampo_condizionamento'),
                                    $get('stampo_tipo_condizionamento'),
                                    $get('stampo_subtipo_condizionamento_fm')
                                )
                            )
                            ->columnSpan('full'),
                        Forms\Components\TextInput::make('stampo_temperatura_fm')
                            ->label('Temperatura f/m')
                            ->required(
                                fn (\Closure $get) => Stampo::HiddenRequired_temperatura_fm(
                                    'required',
                                    $get('stampo_condizionamento'),
                                    $get('stampo_tipo_condizionamento'),
                                    $get('stampo_subtipo_condizionamento_fm')
                                )
                            )
                            ->numeric()
                            ->hidden(
                                fn (\Closure $get) => Stampo::HiddenRequired_temperatura_fm(
                                    'hidden',
                                    $get('stampo_condizionamento'),
                                    $get('stampo_tipo_condizionamento'),
                                    $get('stampo_subtipo_condizionamento_fm')
                                )
                            )
                            ->columnSpan('full'),
                    ]),
            ]),
        Card::make()
            ->schema([
                Forms\Components\Toggle::make('serve_robot')
                ->required()
                ->columnSpan([
                    'default' => 12,
                    'md' => 12,
                    'lg' => 12,
                ]),
            Forms\Components\Toggle::make('stampaggio_automatico')
                ->required()
                ->columnSpan([
                    'default' => 12,
                    'md' => 12,
                    'lg' => 12,
                ]),
                Repeater::make('presse')
                    ->relationship('presse')
                    ->schema([
                        Forms\Components\Select::make('pressa_id')
                            ->label('Pressa')
                            ->options(Pressa::all()->pluck('codiceDescrizione', 'id'))
                            // ->options(
                            //     function () {
                            //         //dd(Pressa::query()->get()->pluck('codiceDescrizione', 'id'));
                            //         Pressa::query()->get()->pluck('codiceDescrizione', 'id');
                            //     }
                            // )
                            //->relationship('presse', 'codiceDescrizione')
                            // ->options(
                            //     fn (\Closure $get) =>
                            //     Articolo::where('cliente_id', '=', $get('data.cliente_id', true))
                            //     ->get()->pluck('codiceDescrizione', 'id')
                            // )
                            ->required()
                            ->getSearchResultsUsing(
                                fn (string $query, \Closure $get) =>
                                Pressa::where('codice', 'like', "%{$query}%")
                                    ->orWhere('descrizione', 'like', "%{$query}%")
                                    ->get()->pluck('codiceDescrizione', 'id')
                            )
                            ->searchable()
                            //->reactive()
                            //->afterStateUpdated(fn ($state, callable $set) => $set('unit_price', Product::find($state)?->price ?? 0))
                            ->columnSpan([
                                'md' => 5,
                            ]),
                    ])
                    ->defaultItems(1)
                    ->createItemButtonLabel('Aggiungi pressa'),
            ]),

        Card::make()
            ->schema([
                Forms\Components\Select::make('polimero_id')
                    ->label('Polimero')
                    //->relationship('articoli', 'codiceDescrizione')
                    ->options(
                        Articolo::where('tipologia', '=', Articolo::POLIMERO)
                            ->get()->pluck('codiceDescrizione', 'id')
                    )
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(
                    function ($state, \Closure $get, callable $set) {
                        //var_dump($get('articolo_id'));
                        // dd($get('data.polimero_id', true));
                        $polimero = Articolo::where('id', '=', $get('data.polimero_id', true))->first();
                        // dd($polimero->condizionato);
                        $set('data.polimero_condizionamento', $polimero->condizionato, true);
                        $set('data.polimero_temperatura', $polimero->condizionamento_temperatura, true);
                        $set('data.polimero_tempo', $polimero->condizionamento_tempo, true);

                    }
                    )
                    ->getSearchResultsUsing(
                        fn (string $query) =>
                        Articolo::where('tipologia', '=', Articolo::POLIMERO)
                            ->where(function ($internalQuery) use ($query) {
                                $internalQuery->where('codice', 'like', "%{$query}%")
                                    ->orWhere('descrizione', 'like', "%{$query}%");
                            })
                            ->get()->pluck('codiceDescrizione', 'id')
                    )
                    ->searchable()
                    ->columnSpan([
                        'md' => 5,
                    ]),

                Grid::make(['default' => 1])
                    ->schema([
                        Card::make()
                            ->schema([
                                Forms\Components\Toggle::make('polimero_condizionamento')
                                    ->disabled()
                                    ->label(
                                        'Condizionamento (selezionare polimero)'
                                    )
                                    ->reactive()
                                    ->columnSpan([
                                        'default' => 8,
                                        'md' => 8,
                                        'lg' => 8,
                                    ]),

                                Grid::make([
                                        'default' => 12,
                                    ])
                                        ->schema([
                                            Forms\Components\TextInput::make('polimero_temperatura')
                                                ->label('Condizionamento Temperatura')
                                                ->integer()
                                                ->disabled()
                                                ->columnSpan([
                                                    'default' => 6,
                                                    'md' => 6,
                                                    'lg' => 6,
                                                ]),

                                            Forms\Components\TextInput::make('polimero_tempo')
                                                ->label('Condizionamento Tempo')
                                                ->integer()
                                                ->disabled()
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
                            ]),

                    ]),
                //->hidden(fn (\Closure $get) => $get('condizionamento') !== true ? true : false),

                Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('peso_matarozza')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(
                                function ($state, \Closure $get, callable $set) {
                                    $rows = $get('data.articoli', true);
                                    //dd($rows);
                                    $somma = 0;
                                    foreach ($rows as $row) {
                                        //dd($row['numero_impronte']);
                                        $somma += ($row['numero_impronte'] * $row['peso_impronte']);
                                    }
                                    //dd($get('data.peso_matarozza',true));
                                    $somma += $get('data.peso_matarozza',true);
                                    $set('data.peso_stampata', $somma, true);
                                }
                            )
                            ->numeric()
                            ->placeholder('Peso Matarozza'),
                        Forms\Components\TextInput::make('peso_stampata')
                            ->required()
                            ->disabled()
                            ->numeric()
                            ->placeholder('Peso Stampate'),
                        Forms\Components\TextInput::make('tempo_ciclo')
                            ->required()
                            ->numeric()
                            ->placeholder('Tempo Cicli Stampaggi'),
                    ])
                    ->columns(3)
                    ->columnSpan([
                        'default' => 12,
                        'md' => 12,
                        'lg' => 12,
                    ]),
                Card::make()
                    ->schema([
                        // Forms\Components\TextInput::make('percentuale_materiale_vergine')
                        //     // ->required()
                        //     ->numeric()
                        //     ->minValue(0)
                        //     ->maxValue(100)
                        //     ->placeholder('Percentuale Materiale Vergine'),

                        Forms\Components\TextInput::make('percentuale_materiale_macinato')
                            // ->required()
                            ->label('Percentuale consigliata di materiale macinato')
                            ->default(0)
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->placeholder('Percentuale consigliata di materiale macinato'),

                        // Forms\Components\TextInput::make('numero_inserti_necessari')
                        //     ->required()
                        //     ->numeric()
                        //     ->placeholder('Numero Inserti Necessari'),

                        Forms\Components\Toggle::make('plus_fasi_stampaggio')
                            ->required(),
                    ])
                    ->disableLabel()
                    ->columns(2)
                    ->columnSpan('full'),

            ]),



        Card::make()
            ->schema([
                Repeater::make('masters')
                    ->relationship('masters')
                    ->schema([
                        Forms\Components\Select::make('articolo_master_id')
                            ->label('Master')
                            //->relationship('articoli', 'codiceDescrizione')
                            ->options(
                                Articolo::where('tipologia', '=', Articolo::MASTER)
                                    ->get()->pluck('codiceDescrizione', 'id')
                            )
                            //->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('colore', Articolo::find($state)?->colore_master ?? 0))
                            ->getSearchResultsUsing(
                                fn (string $query) =>
                                Articolo::where('tipologia', '=', Articolo::MASTER)
                                    ->where(function ($internalQuery) use ($query) {
                                        $internalQuery->where('codice', 'like', "%{$query}%")
                                            ->orWhere('descrizione', 'like', "%{$query}%");
                                    })
                                    ->get()->pluck('codiceDescrizione', 'id')
                            )
                            ->searchable()
                            //->reactive()
                            //->afterStateUpdated(fn ($state, callable $set) => $set('unit_price', Product::find($state)?->price ?? 0))
                            ->columnSpan([
                                'md' => 5,
                            ]),
                        Forms\Components\TextInput::make('colore')
                            ->label('Colore')
                            ->disabled()
                            ->columnSpan([
                                'md' => 2,
                            ]),
                        //->required(),
                        Forms\Components\TextInput::make('percentuale')
                            ->label('Percentuale')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            // ->mask(
                            //     fn (Forms\Components\TextInput\Mask $mask) => $mask
                            //         ->numeric()
                            //         ->decimalPlaces(3)
                            //         ->thousandsSeparator('.')
                            //         ->decimalSeparator(',')
                            // )
                            //->required()
                            ->columnSpan([
                                'md' => 3,
                            ]),
                    ])
                    ->dehydrated()
                    ->defaultItems(0)
                    ->label('Masters')
                    ->createItemButtonLabel('Aggiungi master')
                    //->disableLabel(fn (\Closure $get) => $get('cliente_id') > 0)
                    ->columns([
                        'md' => 10,
                    ])
                //->required()
                //->disabled(fn (\Closure $get) => $get('cliente_id') <= 0),
            ]),
        Card::make()
            ->schema([
                Repeater::make('inserti')
                    ->relationship('inserti')
                    ->schema([
                        Forms\Components\Select::make('articolo_inserto_id')
                            ->label('Inserto')
                            //->relationship('articoli', 'codiceDescrizione')
                            ->options(
                                Articolo::where('tipologia', '=', Articolo::INSERTO)
                                    ->get()->pluck('codiceDescrizione', 'id')
                            )
                            //->required()
                            //->reactive()
                            //->afterStateUpdated(fn ($state, callable $set) => $set('colore', Articolo::find($state)?->colore_master ?? 0))
                            ->getSearchResultsUsing(
                                fn (string $query) =>
                                Articolo::where('tipologia', '=', Articolo::INSERTO)
                                    ->where(function ($internalQuery) use ($query) {
                                        $internalQuery->where('codice', 'like', "%{$query}%")
                                            ->orWhere('descrizione', 'like', "%{$query}%");
                                    })
                                    ->get()->pluck('codiceDescrizione', 'id')
                            )
                            ->searchable()
                            //->reactive()
                            //->afterStateUpdated(fn ($state, callable $set) => $set('unit_price', Product::find($state)?->price ?? 0))
                            ->columnSpan([
                                'md' => 5,
                            ]),
                        Forms\Components\TextInput::make('qta')
                            ->label('Quantità')
                            ->numeric()
                            ->mask(
                                fn (Forms\Components\TextInput\Mask $mask) => $mask
                                    ->numeric()
                                    ->thousandsSeparator('.')
                            )
                            //->required(fn ($state, callable $set) => $set('unit_price', Product::find($state)?->price ?? 0))
                            ->columnSpan([
                                'md' => 3,
                            ]),
                    ])
                    ->dehydrated()
                    ->defaultItems(0)
                    ->label('Inserti')
                    ->createItemButtonLabel('Aggiungi inserto')
                    //->disableLabel(fn (\Closure $get) => $get('cliente_id') > 0)
                    ->columns([
                        'md' => 10,
                    ])
                //->required()
                //->disabled(fn (\Closure $get) => $get('cliente_id') <= 0),
            ]),

        Card::make()
            ->schema([
                Repeater::make('stampati')
                    ->relationship('stampati')
                    ->schema([
                        Forms\Components\Select::make('articolo_stampato_id')
                            ->label('Stampato')
                            //->relationship('articoli', 'codiceDescrizione')
                            ->options(
                                Articolo::where('tipologia', '=', Articolo::STAMPATO)
                                    ->get()->pluck('codiceDescrizione', 'id')
                            )
                            ->required()
                            //->reactive()
                            //->afterStateUpdated(fn ($state, callable $set) => $set('colore', Articolo::find($state)?->colore_master ?? 0))
                            ->getSearchResultsUsing(
                                fn (string $query) =>
                                Articolo::where('tipologia', '=', Articolo::STAMPATO)
                                    ->where(function ($internalQuery) use ($query) {
                                        $internalQuery->where('codice', 'like', "%{$query}%")
                                            ->orWhere('descrizione', 'like', "%{$query}%");
                                    })
                                    ->get()->pluck('codiceDescrizione', 'id')
                            )
                            ->searchable()
                            //->reactive()
                            //->afterStateUpdated(fn ($state, callable $set) => $set('unit_price', Product::find($state)?->price ?? 0))
                            ->columnSpan([
                                'md' => 5,
                            ]),
                        TextInput::make('qta')
                            ->label('Quantità')
                            ->numeric()
                            ->mask(
                                fn (Forms\Components\TextInput\Mask $mask) => $mask
                                    ->numeric()
                                    ->thousandsSeparator('.')
                            )
                            //->required()
                            ->columnSpan([
                                'md' => 3,
                            ]),
                    ])
                    ->dehydrated()
                    ->defaultItems(0)
                    ->createItemButtonLabel('Aggiungi stampato')
                    ->label('Stampati')
                    //->disableLabel(fn (\Closure $get) => $get('cliente_id') > 0)
                    ->columns([
                        'md' => 10,
                    ])
                //->required()
                //->disabled(fn (\Closure $get) => $get('cliente_id') <= 0),
            ]),


        Card::make()
            ->schema([
                Radio::make('colore')
                    ->label('Colore')
                    ->inline()
                    ->required()
                    // ->default(Pfcmadre::NERO)
                    ->options(Pfcmadre::getColore())
            ]),


            Card::make()
            ->schema([
                TinyEditor::make('nota')
                ->language('it_IT')
                ->columnSpan([
                    'default' => 12,
                    'md' => 12,
                    'lg' => 12,
                ]),
            ]),


        ];
    }
}
