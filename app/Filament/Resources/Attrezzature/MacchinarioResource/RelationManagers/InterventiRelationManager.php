<?php

namespace App\Filament\Resources\Attrezzature\MacchinarioResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Intervento;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\RelationManagers\HasManyRelationManager;

class InterventiRelationManager extends HasManyRelationManager
{
    protected static string $relationship = 'interventi';

    protected static ?string $recordTitleAttribute = 'elemento_id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('descrizione')->required(),
                DatePicker::make('data')
                ->default(now())
                ->required()
                ->displayFormat('d/m/Y'),
                Forms\Components\TextInput::make('elemento')
                ->label('Elemento')
                ->disabled(true)
                ->default(Intervento::MACCHINARIO),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('descrizione'),
                Tables\Columns\TextColumn::make('data')
                ->date('d/m/Y'),
            ])
            ->filters([
                //
            ]);
    }
}
