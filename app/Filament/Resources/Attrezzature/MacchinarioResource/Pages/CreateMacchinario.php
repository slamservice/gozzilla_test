<?php

namespace App\Filament\Resources\Attrezzature\MacchinarioResource\Pages;

use App\Filament\Resources\Attrezzature\MacchinarioResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMacchinario extends CreateRecord
{
    protected static string $resource = MacchinarioResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
