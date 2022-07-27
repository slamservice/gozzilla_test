<?php

namespace App\Filament\Resources\PfcResource\Pages;

use Filament\Pages\Actions\Action;
use App\Filament\Resources\PfcResource;
use Filament\Resources\Pages\EditRecord;

class EditPfc extends EditRecord
{

    protected static string $resource = PfcResource::class;
//     protected function getActions(): array
// {
//     return [
//         Action::make('settings')
//             ->label('Settings')
//             ->url("www.slamservice.it"),
//     ];
// }

//     protected function mutateFormDataBeforeFill(array $data): array
// {
//     dd($data);
//     $data['user_id'] = auth()->id();

//     return $data;
// }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['verifica_rapporto_codici'])) {

        } else {
            $data['verifica_rapporto_codici'] = $data['verifica_rapporto_codici_1'];
            unset($data['verifica_rapporto_codici_1']);
        }

        return array_merge(['totali' => 0], $data);
    }

}
