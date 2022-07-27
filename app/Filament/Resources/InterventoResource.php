<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Intervento;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\InterventoResource\Pages;
use App\Filament\Resources\InterventoResource\RelationManagers;

class InterventoResource extends Resource
{
    protected static ?string $model = Intervento::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
        //**Customitations */
        //protected static ?string $recordTitleAttribute = '';

        protected static ?string $slug = 'interventi';

        protected static ?string $label = 'intervento';

        protected static ?string $pluralLabel = 'interventi';

        protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('descrizione')->required(),
                DatePicker::make('data')
                ->required()
                ->default(now())
                ->displayFormat('d/m/Y'),

                Forms\Components\Radio::make('elemento')
                ->label('Elemento')
                ->inline()
                ->options(Intervento::getTipoElemento()),

                // Forms\Components\Select::make('elemento_id')
                // ->label('Elemento id')
                // ->options(
                //     function (\Closure $get) {
                //         switch ($get('elemento')) {
                //             case Intervento::PRESSA :
                //                 $resultElemento = Intervento->pressa();
                //                 break;
                //             case INTERVENTO::STAMPO :
                //                 $resultElemento = $this->belongsTo(Stampo::class, 'elemento_id');
                //                 break;
                //             case Intervento::ESSICATORE :
                //                 $resultElemento = $this->belongsTo(Essicatore::class, 'elemento_id');
                //                 break;
                //             case INTERVENTO::MACCHINARIO :
                //                 $resultElemento = $this->belongsTo(Macchinario::class, 'elemento_id');
                //                 break;
                //             default:
                //                 $resultElemento = null;
                //                 break;
                //         }
                //         return Intervento::elementi($get('elemento'));
                //     }
                // )
                // ->required()
                // ->reactive()
                // ->afterStateUpdated(fn ($state, callable $set) => $set('condizionamento', Articolo::find($state)?->condizionato ?? 0))
                // ->getSearchResultsUsing(
                //     fn (string $query) =>
                //     Articolo::where('tipologia', '=', Articolo::POLIMERO)
                //         ->where(function ($internalQuery) use ($query) {
                //             $internalQuery->where('codice', 'like', "%{$query}%")
                //                 ->orWhere('descrizione', 'like', "%{$query}%");
                //         })
                //         ->limit(50)->get()->pluck('codiceDescrizione', 'id')
                // )
                // ->searchable()
                // //->reactive()
                // //->afterStateUpdated(fn ($state, callable $set) => $set('unit_price', Product::find($state)?->price ?? 0))
                // ->columnSpan([
                //     'md' => 5,
                // ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('descrizione'),
                Tables\Columns\TextColumn::make('data')
                ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('elemento'),
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
            'index' => Pages\ListInterventos::route('/'),
            'create' => Pages\CreateIntervento::route('/create'),
            'edit' => Pages\EditIntervento::route('/{record}/edit'),
        ];
    }
}
