<?php

namespace App\Filament\Resources;

use App\Models\Pfc;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use App\Models\Pfcmadre;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use App\Models\Anagrafiche\Stampo;
use App\Models\Anagrafiche\Cliente;
use App\Models\Attrezzature\Pressa;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use App\Models\Anagrafiche\Articolo;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\PfcResource\Pages;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\HasManyRepeater;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;


class PfcResource extends Resource
{

    protected static ?string $model = Pfc::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    //**Customitations */
    protected static ?string $recordTitleAttribute = 'codice';

    protected static ?string $slug = 'pfc';

    protected static ?string $label = 'pfc';

    protected static ?string $pluralLabel = 'pfc';

    protected static ?int $navigationSort = 1;



    //     protected function getActions(): array
// {
//     return [
//         Action::make('preview')
//         ->label('Vedi')
//         ->modalHeading(function ($record) {
//             $testo = 'Numero PFC: ' . $record->codice;
//             // $testo .= 'Cliente: '.$record->cliente->codiceNome;
//             // $testo .= 'Codice: '.$record->pfcmadre->codice;
//             // $testo .= 'Numero Ordine: '.$record->num_ordine;
//             return new \Illuminate\Support\HtmlString($testo);
//         })
//         ->form(static::PfcPreview(),)
//     ];
// }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //******START CREATION */
                Card::make()
                    ->schema([
                        TextInput::make('codice')
                            ->label('Numero PFC')
                            ->hiddenOn(Pages\CreatePfc::class)
                            ->columnSpan([
                                'default' => 1,
                            ]),
                        BelongsToSelect::make('cliente_id')
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
                            )->columnSpan([
                                'default' => 1,
                            ]),
                        TextInput::make('num_ordine')
                            ->label('Numero Ordine')
                            ->columnSpan([
                                'default' => 1,
                            ]),

                        Select::make('pfcmadre_id')
                            ->label('Codice')
                            ->hidden(fn (\Closure $get) => $get('data.cliente_id', true) > 0 ? false : true)
                            ->getOptionLabelUsing(fn ($value): ?string => Pfcmadre::find($value)?->codice)
                            ->options(
                                fn (\Closure $get) =>
                                Pfcmadre::where('cliente_id', '=', $get('data.cliente_id', true))
                                    ->get()->pluck('codice', 'id')
                            )
                            ->reactive()
                            ->required()
                            ->getSearchResultsUsing(
                                fn (string $query, \Closure $get) =>
                                Pfcmadre::where('cliente_id', '=', $get('data.cliente_id', true))
                                    ->where(function ($internalQuery) use ($query) {
                                        $valore = str::replace(' ', '%', $query);
                                        $internalQuery->where('codice', 'like', "%{$valore}%");
                                    })
                                    ->get()->pluck('codice', 'id')
                            )
                            ->searchable()
                            ->columnSpan([
                                'default' => 2,
                            ])
                    ])->columns(2)
                    ->hidden(fn (Component $livewire) => $livewire instanceof Pages\EditPfc),
                //******END CREATION */

                //******START HEAD */
                Card::make()
                    ->schema([
                        Placeholder::make('codice')
                            ->label('Numero PFC')
                            ->content(fn ($record) => $record->codice)
                            ->columnSpan(1),
                        Placeholder::make('cliente')
                            ->content(fn ($record) => $record->cliente->codiceNome)
                            ->columnSpan(1),
                        Placeholder::make('pfcmadre')
                            ->label('Codice')
                            ->content(fn ($record) => $record->pfcmadre->codice)
                            ->columnSpan(1),
                        TextInput::make('num_ordine')
                            ->label('Numero Ordine')
                            ->columnSpan(1),
                    ])->columns(4)
                    ->hidden(fn (Component $livewire) => $livewire instanceof Pages\CreatePfc),
                //******END HEAD */

                //******START ARTICOLI */
                Card::make()
                    ->columns(4)
                    ->hidden(fn (Component $livewire) => $livewire instanceof Pages\CreatePfc)
                    ->schema([
                        TextInput::make('totali')
                            ->label('Totali quantità ordine')
                            ->disabled()
                            ->hidden()
                            ->dehydrated()
                            ->extraInputAttributes(['class' => 'slam-disabled-style'])
                            ->columnSpan(1),

                        Repeater::make('articoli')
                            ->relationship('articoli')
                            ->dehydrated()
                            ->disableItemCreation()
                            ->disableItemDeletion()
                            ->columns(5)
                            ->columnSpan(4)
                            ->extraAttributes(['class' => 'slam-disabled-style'])
                            ->schema([
                                TextInput::make('articolo_id')
                                    ->label('Articolo')
                                    ->hidden()
                                    ->disabled(),
                                Placeholder::make('Label')
                                    ->disableLabel()
                                    ->content(function (\Closure $get) {
                                        $articolo = Articolo::where('id', '=', $get('articolo_id'))->first();
                                        return (!$articolo) ? '' : $articolo->codiceDescrizione;
                                    })
                                    ->columnSpan(2),

                                TextInput::make('num_stampate')
                                    ->disabled()
                                    ->extraInputAttributes(['class' => 'slam-disabled-style']),


                                // ->disabled()
                                // ->afterStateHydrated(function (TextInput $component, $state, \Closure $get) {
                                //     $state = Articolo::getEsistenza($get('articolo_id'));
                                //     $component->state($state);
                                // })
                                // ->extraInputAttributes(['class' => 'slam-disabled-style']),

                                TextInput::make('subtotale')
                                    ->disabled()
                                    ->extraInputAttributes(['class' => 'slam-disabled-style']),

                                TextInput::make('numero_impronte')
                                    ->disabled()
                                    ->extraInputAttributes(['class' => 'slam-disabled-style']),

                                // TextInput::make('peso_impronte')
                                // ->disabled()
                                // ->extraInputAttributes(['class' => 'slam-disabled-style']),
                                TextInput::make('scorta_a_magazzino')
                                    ->label('Quantità a Magazzino')
                                    ->numeric()
                                    ->reactive()
                                    ->afterStateUpdated(
                                        function ($state, \Closure $get, callable $set, $livewire) {
                                            static::CalcoliQta($state, $get, $set, $livewire);
                                        }
                                    ),

                                TextInput::make('scorta_per_magazzino')
                                    ->label('Scorta per Magazzino')
                                    ->numeric()
                                    ->reactive()
                                    ->afterStateUpdated(
                                        function ($state, \Closure $get, callable $set, $livewire) {
                                            static::CalcoliQta($state, $get, $set, $livewire);
                                        }
                                    ),

                                HasManyRepeater::make('dateConsegna')
                                    ->relationship('dateConsegna')
                                    ->registerListeners([
                                        'repeater::deleteItem' => [
                                            function ($state, \Closure $get, callable $set, $livewire) {
                                                static::CalcoliQta($state, $get, $set, $livewire);
                                            },
                                        ],
                                    ])
                                    ->schema([
                                        DatePicker::make('data_consegna')
                                            ->required()
                                            ->default(now())
                                            ->displayFormat('d/m/Y'),
                                        TextInput::make('qta')
                                            ->label('Quantità ordine')
                                            ->required()
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(
                                                function ($state, \Closure $get, callable $set, $livewire) {
                                                    static::CalcoliQta($state, $get, $set, $livewire);
                                                }
                                            )
                                    ])->columns(2)
                                    ->columnSpan(5)
                                    ->minItems(1),
                            ]),
                        TextInput::make('verifica_rapporto_codici')
                            ->label('Verifica Rapporto Codici')
                            ->reactive()
                            ->hidden(
                                function ($state) {
                                    if ($state == 'OK') {
                                        return false;
                                    } else {
                                        return true;
                                    }
                                }
                            )
                            ->disabled()

                            ->extraInputAttributes(['class' => 'slam-verifica-green-style'])
                            ->columnSpan(4),
                        TextInput::make('verifica_rapporto_codici_1')
                            ->label('Verifica Rapporto Codici')
                            ->reactive()
                            ->hidden(
                                function ($state) {
                                    if ($state == 'OK' or $state == '') {
                                        return true;
                                    } else {
                                        return false;
                                    }
                                }
                            )
                            ->disabled()

                            ->extraInputAttributes(['class' => 'slam-verifica-red-style'])
                            ->columnSpan(4),

                    ]),
                //******STOP ARTICOLI */


                //****NOTE INIZIO */
                Card::make()
                    ->schema([
                        TinyEditor::make('nota')
                            ->language('it_IT')
                            ->columnSpan([
                                'default' => 12,
                                'md' => 12,
                                'lg' => 12,
                            ]),
                    ])
                    ->columns(1)
                    ->extraAttributes(['class' => 'bg-gray-50'])
                    ->hidden(fn (Component $livewire) => $livewire instanceof Pages\CreatePfc),
                //****NOTE FINE */
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('codice')
                    ->label('Numero PFC')
                    ->limit(50),
                TextColumn::make('cliente.nome')
                    ->label('Cliente')
                    ->limit(50),
                TextColumn::make('pfcmadre.codice')
                    ->label('Codice')
                    ->limit(50),
            ])
            ->actions([
                ViewAction::make('preview')
                    ->label('Vedi')
                    ->modalHeading(function ($record) {
                        $testo = 'Numero PFC: ' . $record->codice;
                        // $testo .= 'Cliente: '.$record->cliente->codiceNome;
                        // $testo .= 'Codice: '.$record->pfcmadre->codice;
                        // $testo .= 'Numero Ordine: '.$record->num_ordine;
                        return new \Illuminate\Support\HtmlString($testo);
                    })
                    ->form(static::PfcPreview(),),
                EditAction::make('edit'),
            ])
            ->filters([
                //
            ]);
    }

    public static function getCodiceDescrizioneArticolo(\Closure $get)
    {
        $articolo = Articolo::where('id', '=', $get('articolo_id'))->first();
        return $articolo->codiceDescrizione;
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
            'index' => Pages\ListPfcs::route('/'),
            'create' => Pages\CreatePfc::route('/create'),
            'edit' => Pages\EditPfc::route('/{record}/edit'),
        ];
    }

    public static function CalcoliQta($state, \Closure $get, callable $set, $livewire)
    {
        // function ($state, \Closure $get, callable $set, $livewire) {
        $articoli = $get('data.articoli', true);
        //dd($articoli);
        $subsomma = 0;
        $somma = 0;
        $scortaPerMagazzino = 0;
        $scortaAMagazzino = 0;
        $subtotali = [];
        $impronte = [];

        foreach ($articoli as $articolo) {
            //dd($livewire->data['articoli']['record-'.$articolo['id']]);
            $dateconsegna = $articolo['dateConsegna'];
            // if ($fromscorta == false) {
            if (isset($articolo['scorta_per_magazzino'])) {
                $scortaPerMagazzino = (int)$articolo['scorta_per_magazzino'];
            } else {
                $scortaPerMagazzino = 0;
            }

            if (isset($articolo['scorta_a_magazzino'])) {
                $scortaAMagazzino = (int)$articolo['scorta_a_magazzino'];
            } else {
                $scortaAMagazzino = 0;
            }
            // } else {
            //     $scortaPerMagazzino = (int)$state;
            // }
            foreach ($dateconsegna as $dataconsegna) {
                // dd($dataconsegna);
                if (isset($dataconsegna['qta'])) {
                    $somma += (int)$dataconsegna['qta'];
                    $subsomma += (int)$dataconsegna['qta'];
                } else {
                    $somma += 0;
                    $subsomma += 0;
                }
            }
            // if (isset($articolo['articolo_id'])) {
            //     $qtaMagazzino = (int)Articolo::getEsistenza($articolo['articolo_id']);
            // } else {
            //     $qtaMagazzino = 0;
            // }
            //$qtaMagazzino = (int)$articolo['scorta_a_magazzino'];
            //dd($articolo);
            //dump(Articolo::getEsistenza($articolo['id']));
            // $set('data.subtotali', $subsomma, true);
            // $articolo['subtotali'] = $subsomma;


            $subtotale = $subsomma + ($scortaPerMagazzino - $scortaAMagazzino);

            $livewire->data['articoli']['record-' . $articolo['id']]['subtotale'] = $subtotale;

            if ($livewire->data['articoli']['record-' . $articolo['id']]['numero_impronte'] > 0) {
                $livewire->data['articoli']['record-' . $articolo['id']]['num_stampate'] = intdiv($subtotale, $livewire->data['articoli']['record-' . $articolo['id']]['numero_impronte']);
            } else {
                $livewire->data['articoli']['record-' . $articolo['id']]['num_stampate'] = 0;
            }



            array_push($subtotali, $livewire->data['articoli']['record-' . $articolo['id']]['subtotale']);
            array_push($impronte, $livewire->data['articoli']['record-' . $articolo['id']]['numero_impronte']);

            // if (isset($collection)) {

            //     $collection->push(['subtotale'=>$livewire->data['articoli']['record-'.$articolo['id']]['subtotale']]);
            //     $collection->push(['impronte'=>$livewire->data['articoli']['record-'.$articolo['id']]['numero_impronte']]);
            // } else {
            //     $collection = collect([['subtotale'=>$livewire->data['articoli']['record-'.$articolo['id']]['subtotale']]]);
            //     $collection->push(['impronte'=>$livewire->data['articoli']['record-'.$articolo['id']]['numero_impronte']]);
            // }
            // dump($subsomma);
            // dump($scortaMagazzino);
            // dump($qtaMagazzino);
            //$set('subtotale', $subsomma);
            $qtaMagazzino = 0;
            $subsomma = 0;
        }
        $conta = 0;
        $uno = 0;
        $due = 0;
        $risultatoSubtotali = 0;
        foreach ($subtotali as $key => $value) {
            $conta += 1;
            //dump($value);
            if ($conta == 1) {
                $uno = (int)$value;
            } else {
                $due = (int)$value;
                if ($due > 0) {
                    $risultatoSubtotali = intdiv($uno, $due);
                } else {
                    $risultatoSubtotali = 0;
                }

                $uno = $risultatoSubtotali;
            }
        }
        $conta = 0;
        $uno = 0;
        $due = 0;
        $risultatoImpronte = 0;
        foreach ($impronte as $key => $value) {
            $conta += 1;
            //dump($value);
            if ($conta == 1) {
                $uno = (int)$value;
            } else {
                $due = (int)$value;
                if ($due > 0) {
                    $risultatoImpronte = intdiv($uno, $due);
                } else {
                    $risultatoImpronte = 0;
                }
                $risultatoImpronte = intdiv($uno, $due);
                $uno = $risultatoImpronte;
            }
        }

        if ($risultatoSubtotali == $risultatoImpronte) {
            $set('data.verifica_rapporto_codici', 'OK', true);
            $set('data.verifica_rapporto_codici_1', 'OK', true);
        } else {
            $set('data.verifica_rapporto_codici', 'ATTENZIONE: Verifica Rapporti Codici ERRATA!', true);
            $set('data.verifica_rapporto_codici_1', 'ATTENZIONE: Verifica Rapporti Codici ERRATA!', true);
            Filament::notify('danger', 'ATTENZIONE: Verifica Rapporti Codici ERRATA!');
        }

        $set('data.totali', $somma, true);
        // }
    }

    public static function PfcPreview(): array
    {
        return [
            Card::make()
                ->columns(3)
                ->columnSpan(2)
                ->schema([
                    Placeholder::make('cliente')
                        ->content(fn ($record) => $record->cliente->codiceNome)
                        ->columnSpan(2),
                    Placeholder::make('num_ordine')
                        ->label('Numero Ordine')
                        ->content(fn ($record) => $record->num_ordine)
                        ->columnSpan(1),
                    Placeholder::make('pfcmadre')
                        ->label('Codice')
                        ->content(fn ($record) => $record->pfcmadre->codice)
                        ->columnSpan(3),
                ]),

            //******START ARTICOLI */
            Card::make()
                ->columns(4)
                ->columnSpan(2)
                ->schema([
                    TextInput::make('totali')
                        ->label('Totali quantità ordine')
                        ->disabled()
                        ->hidden()
                        ->dehydrated()
                        ->extraInputAttributes(['class' => 'slam-disabled-style'])
                        ->columnSpan(1),

                    Repeater::make('articoli')
                        ->relationship('articoli')
                        ->dehydrated()
                        ->disableItemCreation()
                        ->disableItemDeletion()
                        ->columns(5)
                        ->columnSpan(4)
                        ->extraAttributes(['class' => 'slam-disabled-style'])
                        ->schema([
                            TextInput::make('articolo_id')
                                ->label('Articolo')
                                ->hidden()
                                ->disabled(),
                            Placeholder::make('Label')
                                ->disableLabel()
                                ->content(function (\Closure $get) {
                                    $articolo = Articolo::where('id', '=', $get('articolo_id'))->first();
                                    return (!$articolo) ? '' : $articolo->codiceDescrizione;
                                })
                                ->columnSpan(5),

                            TextInput::make('num_stampate')
                                ->disabled()
                                ->extraInputAttributes(['class' => 'slam-disabled-style']),


                            // ->disabled()
                            // ->afterStateHydrated(function (TextInput $component, $state, \Closure $get) {
                            //     $state = Articolo::getEsistenza($get('articolo_id'));
                            //     $component->state($state);
                            // })
                            // ->extraInputAttributes(['class' => 'slam-disabled-style']),

                            TextInput::make('subtotale')
                                ->disabled()
                                ->extraInputAttributes(['class' => 'slam-disabled-style']),

                            TextInput::make('numero_impronte')
                                ->disabled()
                                ->extraInputAttributes(['class' => 'slam-disabled-style']),

                            // TextInput::make('peso_impronte')
                            // ->disabled()
                            // ->extraInputAttributes(['class' => 'slam-disabled-style']),
                            TextInput::make('scorta_a_magazzino')
                                ->label('Quantità a Mag.')
                                ->numeric()
                                ->reactive()
                                ->afterStateUpdated(
                                    function ($state, \Closure $get, callable $set, $livewire) {
                                        static::CalcoliQta($state, $get, $set, $livewire);
                                    }
                                ),

                            TextInput::make('scorta_per_magazzino')
                                ->label('Scorta per Mag.')
                                ->numeric()
                                ->reactive()
                                ->afterStateUpdated(
                                    function ($state, \Closure $get, callable $set, $livewire) {
                                        static::CalcoliQta($state, $get, $set, $livewire);
                                    }
                                ),

                            Repeater::make('dateConsegna')
                                ->relationship('dateConsegna')
                                ->registerListeners([
                                    'repeater::deleteItem' => [
                                        function ($state, \Closure $get, callable $set, $livewire) {
                                            static::CalcoliQta($state, $get, $set, $livewire);
                                        },
                                    ],
                                ])
                                ->schema([
                                    DatePicker::make('data_consegna')
                                        ->required()
                                        ->default(now())
                                        ->displayFormat('d/m/Y'),
                                    TextInput::make('qta')
                                        ->label('Quantità ordine')
                                        ->required()
                                        ->numeric()
                                        ->reactive()
                                        ->afterStateUpdated(
                                            function ($state, \Closure $get, callable $set, $livewire) {
                                                static::CalcoliQta($state, $get, $set, $livewire);
                                            }
                                        )
                                ])->columns(2)
                                ->columnSpan(5)
                                ->minItems(1),
                        ]),
                    TextInput::make('verifica_rapporto_codici')
                        ->label('Verifica Rapporto Codici')
                        ->reactive()
                        ->hidden(
                            function ($state) {
                                if ($state == 'OK') {
                                    return false;
                                } else {
                                    return true;
                                }
                            }
                        )
                        ->disabled()
                        ->extraInputAttributes(['class' => 'slam-verifica-green-style'])
                        ->columnSpan(4),
                    TextInput::make('verifica_rapporto_codici_1')
                        ->label('Verifica Rapporto Codici')
                        ->reactive()
                        ->hidden(
                            function ($state) {
                                if ($state == 'OK' or $state == '') {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        )
                        ->disabled()
                        ->extraInputAttributes(['class' => 'slam-verifica-red-style'])
                        ->columnSpan(4),

                ]),
            //******STOP ARTICOLI */

            //**START IMBALLI */
            Card::make()
                ->columns(1)
                ->columnSpan(2)
                ->schema([
                    Repeater::make('imballi')
                        ->relationship('imballi')
                        ->columns(8)
                        ->schema([
                            Placeholder::make('Label')
                                ->disableLabel()
                                ->content(function (\Closure $get) {
                                    $articolo = Articolo::where('id', '=', $get('articolo_imballo_id'))->first();
                                    return (!$articolo) ? '' : $articolo->codiceDescrizione;
                                })
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
                                ->columnSpan(2),
                            Placeholder::make('Label')
                                ->label('Articolo')
                                ->content(function (\Closure $get) {
                                    $articolo = Articolo::where('id', '=', $get('articolo_id'))->first();
                                    return (!$articolo) ? '' : $articolo->codiceDescrizione;
                                })
                                ->columnSpan(3),
                            // Forms\Components\Select::make('articolo_id')
                            // ->label('Articolo')
                            // ->options(
                            //     function ($component) {
                            //         $codearts = array();
                            //         $container = $component->getLivewire()->data['articoli'];
                            //     foreach ($container as $row) {
                            //         array_push($codearts,$row['articolo_id']);
                            //     }
                            //     $test = Articolo::wherein('id', $codearts)
                            //     ->get()->pluck('codiceDescrizione', 'id');
                            //     return $test;
                            //     }
                            // )
                            // ->columnSpan(3),
                        ])
                        ->dehydrated()
                        ->columnSpan(1)
                        ->defaultItems(0)
                        ->createItemButtonLabel('Aggiungi imballo')
                        ->label('Imballi')
                        ->orderable()

                ]),
            //**STOP IMBALLI */

            //**START STAMPO */
            Card::make()
                ->columns(5)
                ->columnSpan(2)
                ->schema([
                    Placeholder::make('Label')
                        ->label('Stampo')
                        ->content(function (\Closure $get) {
                            $stampo = Stampo::where('id', '=', $get('stampo_id'))->first();
                            return (!$stampo) ? '' : $stampo->codiceDescrizione;
                        })
                        ->columnSpan(3),
                    // Forms\Components\BelongsToSelect::make('stampo_id')
                    //     ->relationship('stampo', 'codiceDescrizione')
                    //     ->searchable()
                    //     ->required()
                    //     ->reactive()
                    //     ->afterStateUpdated(
                    //         function ($state, \Closure $get, callable $set) {
                    //             //var_dump($get('articolo_id'));
                    //             $ubicazione = Stampo::find($get('stampo_id'))->ubicazione;
                    //             //dd($ubicazione);
                    //             $set('data.stampo_ubicazione', $ubicazione, true);
                    //         }
                    //     )
                    //     ->options(Stampo::all()->pluck('codiceDescrizione', 'id'))
                    //     ->getSearchResultsUsing(
                    //         fn (string $query) =>
                    //         Stampo::where('codice', 'like', "%{$query}%")
                    //             ->orWhere('descrizione', 'like', "%{$query}%")
                    //             ->get()->pluck('codiceDescrizione', 'id')
                    //     )
                    //     ->columnSpan([
                    //         'default' => 12,
                    //         'md' => 12,
                    //         'lg' => 12,
                    //     ]),
                    Forms\Components\TextInput::make('stampo_ubicazione')
                        ->disabled()
                        ->label('Ubicazione')
                        //->placeholder('Ubicazione')
                        ->columnSpan(2),


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
            //**STOP STAMPO */

            //**START PRESSE */
            Card::make()
                ->columns(4)
                ->columnSpan(2)
                ->schema([
                    Forms\Components\Toggle::make('serve_robot')
                        ->columnSpan(1),
                    Forms\Components\Toggle::make('stampaggio_automatico')
                        ->columnSpan(3),
                    Repeater::make('presse')
                        ->relationship('presse')
                        ->columns(1)
                        ->columnSpan(4)
                        ->schema([
                            Placeholder::make('Label')
                                ->disableLabel()
                                ->content(function (\Closure $get) {
                                    $pressa = Pressa::where('id', '=', $get('pressa_id'))->first();
                                    return (!$pressa) ? '' : $pressa->codiceDescrizione;
                                })
                                ->columnSpan(1),

                        ])
                        ->defaultItems(1)
                        ->createItemButtonLabel('Aggiungi pressa'),
                ]),
            //**STOP PRESSE */

            //**START POLIMERO */
            Card::make()
                ->columns(10)
                ->columnSpan(2)
                ->schema([
                    Placeholder::make('Label')
                        ->label('Polimero')
                        ->content(function (\Closure $get) {
                            $pressa = Articolo::where('id', '=', $get('polimero_id'))->first();
                            return (!$pressa) ? '' : $pressa->codiceDescrizione;
                        })
                        ->columnSpan(3),
                    Card::make()
                    ->columns(3)
                    ->columnSpan(7)
                    ->schema([
                        Forms\Components\Toggle::make('polimero_condizionamento')
                        ->disabled()
                        ->label(
                            'Condizionamento'
                        )
                        ->reactive()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('polimero_temperatura')
                        ->label('Condizionamento Temperatura')
                        ->integer()
                        ->disabled()
                        ->columnSpan(1),

                    Forms\Components\TextInput::make('polimero_tempo')
                        ->label('Condizionamento Tempo')
                        ->integer()
                        ->disabled()
                        ->columnSpan(1)
                    ]),
                    Forms\Components\TextInput::make('peso_matarozza')
                    ->reactive()
                    ->columnSpan(2)
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
                            $somma += $get('data.peso_matarozza', true);
                            $set('data.peso_stampata', $somma, true);
                        }
                    )
                    ->numeric()
                    ->placeholder('Peso Matarozza'),
                Forms\Components\TextInput::make('peso_stampata')
                    ->columnSpan(2)
                    ->disabled()
                    ->numeric()
                    ->placeholder('Peso Stampate'),
                Forms\Components\TextInput::make('tempo_ciclo')
                ->columnSpan(2)
                    ->numeric()
                    ->placeholder('Tempo Cicli Stampaggi'),

                    Forms\Components\TextInput::make('percentuale_materiale_macinato')
                    ->columnSpan(2)
                    ->label('Percentuale consigliata di materiale macinato')
                    ->default(0)
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->placeholder('Percentuale consigliata di materiale macinato'),

                    Forms\Components\Toggle::make('plus_fasi_stampaggio')
                    ->columnSpan(2),

                ]),
            //**STOP POLIMERO */

            //**START MASTERS */
            Card::make()
            ->columnSpan(2)
            ->columns(8)
            ->schema([
                Repeater::make('masters')
                    ->relationship('masters')
                    ->columnSpan(8)
                    ->columns(8)
                    ->schema([
                        Placeholder::make('Label')
                        ->disableLabel()
                        ->content(function (\Closure $get) {
                            $articolo = Articolo::where('id', '=', $get('articolo_master_id'))->first();
                            return (!$articolo) ? '' : $articolo->codiceDescrizione;
                        })
                        ->columnSpan(4),
                        Forms\Components\TextInput::make('colore')
                            ->label('Colore')
                            ->disabled()
                            ->columnSpan(2),
                        //->required(),
                        Forms\Components\TextInput::make('percentuale')
                            ->label('Percentuale')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->columnSpan(2),
                    ])
                    ->dehydrated()
                    ->defaultItems(0)
                    ->label('Masters')
                    ->createItemButtonLabel('Aggiungi master'),

                //->required()
                //->disabled(fn (\Closure $get) => $get('cliente_id') <= 0),
            ]),
            //**STOP MASTERS */

            //**START  INSERTI*/

            Card::make()
            ->columnSpan(2)
            ->columns(8)
                    ->schema([
                        Repeater::make('inserti')
                            ->relationship('inserti')
                            ->columnSpan(8)
                            ->columns(6)
                            ->schema([
                                Placeholder::make('Label')
                                ->disableLabel()
                                ->content(function (\Closure $get) {
                                    $articolo = Articolo::where('id', '=', $get('articolo_inserto_id'))->first();
                                    return (!$articolo) ? '' : $articolo->codiceDescrizione;
                                })
                                ->columnSpan(4),
                                Forms\Components\TextInput::make('qta')
                                    ->label('Quantità')
                                    ->numeric()
                                    ->mask(
                                        fn (Forms\Components\TextInput\Mask $mask) => $mask
                                            ->numeric()
                                            ->thousandsSeparator('.')
                                    )
                                    //->required(fn ($state, callable $set) => $set('unit_price', Product::find($state)?->price ?? 0))
                                    ->columnSpan(2),
                            ])
                            ->dehydrated()
                            ->defaultItems(0)
                            ->label('Inserti')
                            ->createItemButtonLabel('Aggiungi inserto')
                    ]),
            //**STOP INSERTI */

            //**START STAMPATI */
            Card::make()
            ->columnSpan(2)
            ->columns(8)
            ->schema([
                Repeater::make('stampati')
                    ->relationship('stampati')
                    ->columnSpan(8)
                    ->columns(6)
                    ->schema([
                        Placeholder::make('Label')
                        ->disableLabel()
                        ->content(function (\Closure $get) {
                            $articolo = Articolo::where('id', '=', $get('articolo_stampato_id'))->first();
                            return (!$articolo) ? '' : $articolo->codiceDescrizione;
                        })
                        ->columnSpan(4),
                        Forms\Components\TextInput::make('qta')
                            ->label('Quantità')
                            ->numeric()
                            ->mask(
                                fn (Forms\Components\TextInput\Mask $mask) => $mask
                                    ->numeric()
                                    ->thousandsSeparator('.')
                            )
                            //->required()
                            ->columnSpan(2),
                    ])
                    ->dehydrated()
                    ->defaultItems(0)
                    ->createItemButtonLabel('Aggiungi stampato')
                    ->label('Stampati')

            ]),
            //**STOP STAMPATI */
            Card::make()
            ->columnSpan(2)
            ->columns(1)
            ->schema([
                Forms\Components\Radio::make('colore')
                    ->label('Colore')
                    ->inline()
                    ->columnSpan(1)
                    ->options(Pfcmadre::getColore()),
                Placeholder::make('Label')
                    ->label('Nota')
                    ->content(fn ($record) => new \Illuminate\Support\HtmlString($record->nota))
                    ->columnSpan(1),
            ]),
        ];
    }
}
