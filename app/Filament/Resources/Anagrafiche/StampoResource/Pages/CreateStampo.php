<?php

namespace App\Filament\Resources\Anagrafiche\StampoResource\Pages;

use App\Filament\Resources\Anagrafiche\StampoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStampo extends CreateRecord
{
    protected static string $resource = StampoResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
