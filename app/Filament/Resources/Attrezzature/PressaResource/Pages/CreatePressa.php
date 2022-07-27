<?php

namespace App\Filament\Resources\Attrezzature\PressaResource\Pages;

use App\Filament\Resources\Attrezzature\PressaResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePressa extends CreateRecord
{
    protected static string $resource = PressaResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
