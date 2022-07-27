<?php

namespace App\Filament\Resources\Anagrafiche\ClienteResource\Pages;

use App\Filament\Resources\Anagrafiche\ClienteResource;
use Filament\Resources\Pages\EditRecord;

class EditCliente extends EditRecord
{
    protected static string $resource = ClienteResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
