<?php

namespace App\Forms\Components;

use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;

class LottogiacenzaForm extends Forms\Components\Field
{
    protected string $view = 'forms::components.group';

    public function getChildComponents(): array
    {
        return [
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
        ];
    }

}
