<?php

namespace App\Models;

use App\Models\Anagrafiche\Stampo;
use App\Models\Attrezzature\Pressa;
use App\Models\Attrezzature\Essicatore;
use Illuminate\Database\Eloquent\Model;
use App\Models\Attrezzature\Macchinario;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Intervento extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'descrizione',
        'elemento',
        'elemento_id',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'interventi';

    const PRESSA = "pressa";
    const STAMPO = "stampo";
    const ESSICATORE = "essicatore";
    const MACCHINARIO = "macchinario";
    public static function getTipoElemento()
    {
        /**
        * @return array<value, label>
        */
        return [
            Intervento::PRESSA => Intervento::PRESSA,
            Intervento::STAMPO => Intervento::STAMPO,
            Intervento::ESSICATORE => Intervento::ESSICATORE,
            Intervento::MACCHINARIO => Intervento::MACCHINARIO,
        ];
    }

    // public static function getElementi($this, $tipoElemento)
    // {
    //     return elementi($this, $tipoElemento);
    // }

    // public function elementi($this, $tipoElemento): BelongsTo
    // {
    //     switch ($tipoElemento) {
    //         case Intervento::PRESSA :
    //             $resultElemento = $this->belongsTo(Pressa::class, 'elemento_id');
    //             break;
    //         case INTERVENTO::STAMPO :
    //             $resultElemento = $this->belongsTo(Stampo::class, 'elemento_id');
    //             break;
    //         case Intervento::ESSICATORE :
    //             $resultElemento = $this->belongsTo(Essicatore::class, 'elemento_id');
    //             break;
    //         case INTERVENTO::MACCHINARIO :
    //             $resultElemento = $this->belongsTo(Macchinario::class, 'elemento_id');
    //             break;
    //         default:
    //             $resultElemento = null;
    //             break;
    //     }
    //     return $resultElemento;
    // }

    public function pressa(): BelongsTo
    {
        return $this->belongsTo(Pressa::class, 'elemento_id');
    }

    public function macchinario(): BelongsTo
    {
        return $this->belongsTo(Macchinario::class, 'elemento_id');
    }

    public function essicatore(): BelongsTo
    {
        return $this->belongsTo(Essicatore::class, 'elemento_id');
    }

    public function stampo(): BelongsTo
    {
        return $this->belongsTo(Stampo::class, 'elemento_id');
    }
}
