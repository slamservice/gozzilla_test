<?php

namespace App\Filament\Resources\Anagrafiche\ArticoloResource\Pages;

use App\Filament\Resources\Anagrafiche\ArticoloResource;
use Filament\Resources\Pages\EditRecord;

class EditArticolo extends EditRecord
{
    protected static string $resource = ArticoloResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
