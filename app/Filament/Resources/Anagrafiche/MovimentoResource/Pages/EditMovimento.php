<?php

namespace App\Filament\Resources\Anagrafiche\MovimentoResource\Pages;

use App\Models\Anagrafiche\Movimento;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Anagrafiche\MovimentoResource;

class EditMovimento extends EditRecord
{
    protected static string $resource = MovimentoResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = array_merge(['qta_scarico' => 0, 'qta_carico' => 0], $data);
        return $data;
    }

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     if ($data['tipo'] == Movimento::CARICO) {
    //         $data['qta_scarico'] == 0;
    //     } else {
    //         $data['qta_carico'] == 0;
    //     }

    //     return $data;
    // }
}
