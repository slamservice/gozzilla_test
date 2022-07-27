<?php

namespace App\Filament\Resources\Anagrafiche\FornitoreResource\Pages;

use App\Filament\Resources\Anagrafiche\FornitoreResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFornitore extends CreateRecord
{
    protected static string $resource = FornitoreResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
