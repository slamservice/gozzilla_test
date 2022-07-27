<?php

namespace App\Filament\Resources\PfcmadreResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\BelongsToManyRelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class PresseRelationManager extends BelongsToManyRelationManager
{
    protected static string $relationship = 'presse';

    protected static ?string $recordTitleAttribute = 'descrizione';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ]);
    }
}
