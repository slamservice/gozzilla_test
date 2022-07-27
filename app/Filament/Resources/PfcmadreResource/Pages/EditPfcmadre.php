<?php

namespace App\Filament\Resources\PfcmadreResource\Pages;

use App\Filament\Resources\PfcmadreResource;
use Filament\Resources\Pages\EditRecord;

class EditPfcmadre extends EditRecord
{
    protected static string $resource = PfcmadreResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
