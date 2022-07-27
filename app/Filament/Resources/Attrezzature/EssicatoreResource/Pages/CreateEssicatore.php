<?php

namespace App\Filament\Resources\Attrezzature\EssicatoreResource\Pages;

use App\Filament\Resources\Attrezzature\EssicatoreResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEssicatore extends CreateRecord
{
    protected static string $resource = EssicatoreResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
