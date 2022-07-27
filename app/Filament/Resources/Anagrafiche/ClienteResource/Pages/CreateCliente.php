<?php

namespace App\Filament\Resources\Anagrafiche\ClienteResource\Pages;

use App\Filament\Resources\Anagrafiche\ClienteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCliente extends CreateRecord
{
    protected static string $resource = ClienteResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

