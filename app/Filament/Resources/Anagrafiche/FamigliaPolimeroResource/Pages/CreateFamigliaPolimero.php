<?php

namespace App\Filament\Resources\Anagrafiche\FamigliaPolimeroResource\Pages;

use App\Filament\Resources\Anagrafiche\FamigliaPolimeroResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFamigliaPolimero extends CreateRecord
{
    protected static string $resource = FamigliaPolimeroResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
