<?php

namespace App\Filament\Resources\PfcResource\Pages;

use App\Models\Pfcmadre;
use App\Models\Contatore;
use App\Models\PfcMaster;
use App\Models\PfcPressa;
use App\Models\PfcImballo;
use App\Models\PfcInserto;
use App\Models\PfcArticolo;
use App\Models\PfcStampato;
use App\Filament\Resources\PfcResource;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use Heloufir\FilamentWorkflowManager\Core\WorkflowResource;

class CreatePfc extends CreateRecord
{
    use WorkflowResource;

    protected static string $resource = PfcResource::class;


    protected function handleRecordCreation(array $data): Model
    {
        $pfc = static::getModel()::create($data);
        $pfcmadre = Pfcmadre::find($data['pfcmadre_id']);
        //articoli
        $articoli = $pfcmadre->articoli;
        foreach ($articoli as $articolo) {
            $articolo_pfc = new PfcArticolo;
            $articolo_pfc->articolo_id = $articolo->articolo_id;
            $articolo_pfc->pfc_id = $pfc['id'];
            $articolo_pfc->numero_impronte = $articolo->numero_impronte;
            $articolo_pfc->peso_impronte = $articolo->peso_impronte;
            $articolo_pfc->save();
        }
        //imballi
        $imballi = $pfcmadre->imballi;
        foreach ($imballi as $imballo) {
            $imballo_pfc = new PfcImballo;
            $imballo_pfc->articolo_imballo_id = $imballo->articolo_imballo_id;
            $imballo_pfc->articolo_id = $imballo->articolo_id;
            $imballo_pfc->pfc_id = $pfc['id'];
            $imballo_pfc->nr_conf_per_scatola = $imballo->nr_conf_per_scatola;
            $imballo_pfc->sort = $imballo->sort;
            $imballo_pfc->save();
        }
        //inserti
        $inserti = $pfcmadre->inserti;
        foreach ($inserti as $inserto) {
            $inserto_pfc = new PfcInserto;
            $inserto_pfc->articolo_inserto_id = $inserto->articolo_inserto_id;
            $inserto_pfc->pfc_id = $pfc['id'];
            $inserto_pfc->qta = $inserto->qta;
            $inserto_pfc->save();
        }
        //masters
        $masters = $pfcmadre->masters;
        foreach ($masters as $master) {
            $master_pfc = new PfcMaster;
            $master_pfc->articolo_master_id = $master->articolo_master_id;
            $master_pfc->pfc_id = $pfc['id'];
            $master_pfc->colore = $master->colore;
            $master_pfc->percentuale = $master->percentuale;
            $master_pfc->save();
        }
        //presse
        $presse = $pfcmadre->presse;
        foreach ($presse as $pressa) {
            $pressa_pfc = new PfcPressa;
            $pressa_pfc->pressa_id = $pressa->pressa_id;
            $pressa_pfc->pfc_id = $pfc['id'];
            $pressa_pfc->serve_robot = $pressa->serve_robot;
            $pressa_pfc->stampaggio_automatico = $pressa->stampaggio_automatico;
            $pressa_pfc->save();
        }
        //stampati
        $stampati = $pfcmadre->stampati;
        foreach ($stampati as $stampato) {
            $stampato_pfc = new PfcStampato;
            $stampato_pfc->articolo_stampato_id = $stampato->articolo_stampato_id;
            $stampato_pfc->pfc_id = $pfc['id'];
            $stampato_pfc->qta = $stampato->qta;
            $stampato_pfc->save();
        }
        return $pfc;
    }


    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $pfcmadre = Pfcmadre::find($data['pfcmadre_id']);

        $data = array_merge([
            'codice' => $this->getContatore(),
            'stampo_id' => $pfcmadre['stampo_id'],
            'stampo_ubicazione' => $pfcmadre->stampo->ubicazione,
            'stampo_condizionamento' => $pfcmadre['stampo_condizionamento'],
            'stampo_tipo_condizionamento' => $pfcmadre['stampo_tipo_condizionamento'],
            'stampo_subtipo_condizionamento' => $pfcmadre['stampo_subtipo_condizionamento'],
            'stampo_numero_linee' => $pfcmadre['stampo_numero_linee'],
            'stampo_temperatura' => $pfcmadre['stampo_temperatura'],
            'stampo_subtipo_condizionamento_fm' => $pfcmadre['stampo_subtipo_condizionamento_fm'],
            'stampo_numero_linee_fm' => $pfcmadre['stampo_numero_linee_fm'],
            'stampo_temperatura_fm' => $pfcmadre['stampo_temperatura_fm'],
            'polimero_id' => $pfcmadre['polimero_id'],
            'polimero_condizionamento' => $pfcmadre['polimero_condizionamento'],
            'polimero_temperatura' => $pfcmadre['polimero_temperatura'],
            'polimero_tempo' => $pfcmadre['polimero_tempo'],
            'peso_matarozza' => $pfcmadre['peso_matarozza'],
            'peso_stampata' => $pfcmadre['peso_stampata'],
            'tempo_ciclo' => $pfcmadre['tempo_ciclo'],
            'serve_robot' => $pfcmadre['serve_robot'],
            'stampaggio_automatico' => $pfcmadre['stampaggio_automatico'],
            'percentuale_materiale_vergine' => $pfcmadre['percentuale_materiale_vergine'],
            'percentuale_materiale_macinato' => $pfcmadre['percentuale_materiale_macinato'],
            'numero_inserti_necessari' => $pfcmadre['numero_inserti_necessari'],
            'plus_fasi_stampaggio' => $pfcmadre['plus_fasi_stampaggio'],
            'colore' => $pfcmadre['colore'],
            'nota' => $pfcmadre['nota'],

        ], $data);

        return $data;
    }


    public function getContatore()
    {
        $contatore = Contatore::where('tipo', '=', 'pfc')
         ->where('anno','=',date('Y'))->first();

        if (!$contatore) {
            $contatore = new Contatore;
            $contatore->tipo = 'pfc';
            $contatore->maschera = '[valore]/[YYYY]';
            $contatore->valore = 0;
            $contatore->cambio_anno = 1;
            $contatore->anno = date('Y');
            $contatore->save();
        }

        $maschera = $contatore['maschera'];

        if (!$contatore['anno'] and $contatore['cambio_anno'] == 1)  {
            $contatore['anno'] = date('Y');
        } else {
            //dd($codice);
        }

        $valore = $contatore['valore']+1;
        $contatore['valore'] = $valore;

        $codice = str_replace('[valore]',$valore,$maschera);
        $codice = str_replace('[YYYY]',date('Y'),$codice);
        $contatore->save();

        return $codice;
    }
}
