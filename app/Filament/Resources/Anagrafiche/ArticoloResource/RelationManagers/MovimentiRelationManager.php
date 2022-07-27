<?php

namespace App\Filament\Resources\Anagrafiche\ArticoloResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use Akaunting\Money\Currency;
use Filament\Resources\Table;
use Filament\Forms\Components\Card;
use App\Models\Anagrafiche\Articolo;
use Filament\Forms\Components\Radio;
use App\Models\Anagrafiche\Magazzino;
use App\Models\Anagrafiche\Movimento;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\RelationManagers\HasManyRelationManager;

class MovimentiRelationManager extends HasManyRelationManager
{
    protected static string $relationship = 'movimenti';

    protected static ?string $recordTitleAttribute = 'lotto';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Forms\Components\Select::make('magazzino_id')
                        ->label('Magazzino')
                        ->options(Magazzino::all()->pluck('codiceDescrizione', 'id'))
                        ->reactive()
                        ->required()
                        ->searchable()
                        ->columnSpan([
                            'sm' => 1,
                        ]),

                        TextInput::make('lotto')
                        ->required(),
                        DatePicker::make('data_lotto')
                        ->required()
                        ->displayFormat('d/m/Y'),
                        Radio::make('tipo')
                        ->Label('Tipo Movimento')
                        ->inline()
                        ->required()
                        ->options(Movimento::getTipoMovimento())
                        ->reactive()
                        ->columnSpan([
                            'sm' => 2,
                        ]),
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

                ])
                ->columns(2)
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
                Tables\Columns\TextColumn::make('lotto')->limit(20),
                Tables\Columns\TextColumn::make('data_lotto')->date('d/m/Y'),
                Tables\Columns\TextColumn::make('tipo'),
                BadgeColumn::make('tipo')
                ->enum(Movimento::getTipoMovimento())
                ->colors(Movimento::getColoriTipoMovimento()),
                Tables\Columns\TextColumn::make('qta_carico')->label('Q.tà Carico'),
                Tables\Columns\TextColumn::make('qta_scarico')->label('Q.tà Scarico'),

            ])
            ->filters([
                //
            ]);
    }
}
