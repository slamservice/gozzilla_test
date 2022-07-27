<?php

namespace App\Filament\Resources\GiacenzaResource\Pages;

use App\Models\Giacenza;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\GiacenzaResource;
use Illuminate\Support\Facades\DB;

class ListGiacenza extends ListRecords
{
    protected static string $resource = GiacenzaResource::class;

    protected function getTableQuery(): Builder
    {
        //$testquery = Giacenza::query();
        $testquery = parent::getTableQuery();
        $testquery->select(DB::raw("min(movimenti.id) as id,
        concat(magazzini.codice,' ',magazzini.descrizione) as magazzino,
        concat(articoli.codice,' ',articoli.descrizione) as articolo,
        sum(movimenti.qta_carico - movimenti.qta_scarico) as giacenza,
        articolo_id, tipologia"));
        $testquery->leftjoin('magazzini','magazzini.id','=','movimenti.magazzino_id');
        $testquery->leftjoin('articoli','articoli.id','=','movimenti.articolo_id');

        $testquery->groupBy('magazzino_id');
        $testquery->groupBy('articolo_id');
        $testquery->groupBy('tipologia');

        // $testquery->orderBy('magazzini.codice', 'ASC');
        // $testquery->orderBy('articoli.codice', 'ASC');
        //dd($testquery);
        return $testquery;
    }

    public function cercaLotti(){
        dd('pippo');
    }
}
