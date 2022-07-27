<?php

namespace App\Filament\Resources\GiacenzaResource\Pages;

use Filament\Pages\Actions;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\GiacenzaResource;

class ManageGiacenza extends ManageRecords
{
    protected static string $resource = GiacenzaResource::class;

    protected function getActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
