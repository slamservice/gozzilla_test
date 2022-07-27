<?php

namespace App\Filament\Resources\Anagrafiche\FornitoreResource\Pages;

use App\Filament\Resources\Anagrafiche\FornitoreResource;
use Filament\Resources\Pages\EditRecord;

class EditFornitore extends EditRecord
{
    protected static string $resource = FornitoreResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
