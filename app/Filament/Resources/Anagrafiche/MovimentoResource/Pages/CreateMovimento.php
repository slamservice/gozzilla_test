<?php

namespace App\Filament\Resources\Anagrafiche\MovimentoResource\Pages;

use App\Models\Anagrafiche\Movimento;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Anagrafiche\MovimentoResource;
use Illuminate\Database\Eloquent\Model;

class CreateMovimento extends CreateRecord
{
    protected static string $resource = MovimentoResource::class;

    // protected function beforeValidate(): void
    // {
    //     // Runs before the form fields are validated when the form is submitted.
    //     dd($this->data);
    // }

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     if ($data['tipo'] == Movimento::CARICO) {
    //         $data['qta_scarico'] == 0;
    //     } else {
    //         $data['qta_carico'] == 0;
    //     }

    //     return $data;
    // }

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
