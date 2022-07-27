<?php

namespace App\Models\Anagrafiche;

use App\Models\Pfcmadre;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Anagrafiche\FamigliaPolimero;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

class Articolo extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;


    protected $fillable = [
        'codice',
        'descrizione',
        'tipologia',
        'cliente_id',
        'prezzo_medio',
        'colore_master',
        'condizionato',
        'condizionamento_temperatura',
        'condizionamento_tempo',
        'famiglia_polimero_id',
        'nota'
    ];


    protected $table = 'articoli';

    protected $casts = [
        'condizionamento' => 'boolean',
    ];

    public function codiceDescrizione(): Attribute
    {
        return Attribute::get(fn() => $this->codice.' - '.$this->descrizione);
    }

    const FRIGO = 'frigo';
    const CENTRALINA = 'centralina';
    public static function getSubTipoCondizionamentoPolimero()
    {
        /**
        * @return array<value, label>
        */
        return [
            Articolo::FRIGO => Articolo::FRIGO,
            Articolo::CENTRALINA => Articolo::CENTRALINA
        ];
    }

    const FISSO = "fisso";
    const MOBILE = "mobile";
    const FISSOeMOBILE = "fisso+mobile";
    const FISSOoMOBILE = "fisso/mobile";
    public static function getTipoCondizionamentoPolimero()
    {
        /**
        * @return array<value, label>
        */
        return [
            Articolo::FISSO => Articolo::FISSO,
            Articolo::MOBILE => Articolo::MOBILE,
            Articolo::FISSOeMOBILE => Articolo::FISSOeMOBILE,
            Articolo::FISSOoMOBILE => Articolo::FISSOoMOBILE
        ];
    }

    const POLIMERO = "polimero";
    const MASTER = "master";
    const MACINATO = "macinato";
    const IMBALLO = "imballo";
    const STAMPATO = "pezzo_stampato";
    const INSERTO = "inserto";
    public static function getTipologieArticolo()
    {
        /**
        * @return array<value, label>
        */
        return [
            Articolo::POLIMERO => Articolo::POLIMERO,
            Articolo::MASTER => Articolo::MASTER,
            Articolo::MACINATO => Articolo::MACINATO,
            Articolo::IMBALLO => Articolo::IMBALLO,
            Articolo::STAMPATO => Articolo::STAMPATO,
            Articolo::INSERTO => Articolo::INSERTO,
        ];
    }

    // public function pfcmadre(): BelongsToMany
    // {
    //     return $this->belongsToMany(Pfcmadre::class, 'pfcmadre_articolo', 'articolo_id', 'pfcmadre_id');
    // }

    public function pfcmadre(): HasMany
    {
        return $this->hasMany(PfcmadreArticolo::class, 'articolo_id');
    }

    public function movimenti(): HasMany
    {
        return $this->hasMany(Movimento::class, 'articolo_id');
    }

    public static function getGiacenza($articolo_id)
    {
        //dd($articolo_id);
        if ($articolo_id > 0) {
            $movimenti = Movimento::select(DB::raw('IFNULL(SUM(qta_carico), 0) as carico'), DB::raw('IFNULL(SUM(qta_scarico), 0) as scarico'))
            ->groupBy('articolo_id')
            ->where('articolo_id','=',$articolo_id)
            ->first();

            if (isset($movimenti)) {
                $giacenza = $movimenti->carico-$movimenti->scarico;
            } else {
                $giacenza = 0;
            }
        } else {
            $giacenza = 0;
        }

        return $giacenza;
    }

    // public function colore()
    // {
    //     return $this->belongsTo(Colore::class);
    // }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function FamigliaPolimero()
    {
        return $this->belongsTo(FamigliaPolimero::class);
    }

    public static function HiddenRequired_numero_linee($tipo,$condizionamento_polimero, $subtipo_condizionamento_polimero): bool
    {
        /**
         * Controlla e restituisce se il campo è visibile o no
         *
         * @param   $tipo                               hidden, required
         * @param   $condizionamento_polimero           prima condizione: se false, restituisce false
         * @param   $tipo_condizionamento_polimero      fisso, mobile, fisso+mobile, fisso/mobile
         * @param   $subtipo_condizionamento_polimero   frigo, centralina
         *
         * @return true/false
        */

        $result = true;

        if ($condizionamento_polimero == false) {
            $result = true;
        } else {
            //var_dump($subtipo_condizionamento_polimero);
            switch ($subtipo_condizionamento_polimero) {
                case Articolo::FRIGO :
                    $result = false;
                    break;
                case Articolo::CENTRALINA :
                    $result = true;
                    break;
                default:
                    $result = true;
                    break;
            }
        }
        if ($tipo == 'hidden') {
            $resrequired = $result;
        } else {
            $resrequired = ($result == true) ? false : true ;
        }
        return $resrequired;
    }

    public static function HiddenRequired_temperatura($tipo,$condizionamento_polimero, $subtipo_condizionamento_polimero): bool
    {
        /**
         * Controlla e restituisce se il campo è visibile o no
         *
         * @param   $tipo                               hidden, required
         * @param   $condizionamento_polimero           prima condizione: se false, restituisce false
         * @param   $tipo_condizionamento_polimero      fisso, mobile, fisso+mobile, fisso/mobile
         * @param   $subtipo_condizionamento_polimero   frigo, centralina
         *
         * @return true/false
        */

        $result = true;

        if ($condizionamento_polimero == false) {
            $result = true;
        } else {
            //var_dump($subtipo_condizionamento_polimero);
            switch ($subtipo_condizionamento_polimero) {
                case Articolo::FRIGO :
                    $result = true;
                    break;
                case Articolo::CENTRALINA :
                    $result = false;
                    break;
                default:
                    $result = true;
                    break;
            }
        }
        if ($tipo == 'hidden') {
            $resrequired = $result;
        } else {
            $resrequired = ($result == true) ? false : true ;
        }
        return $resrequired;
    }

    public static function HiddenRequired_numero_linee_fm($tipo,$condizionamento_polimero, $tipo_condizionamento_polimero, $subtipo_condizionamento_polimero): bool
    {
        /**
         * Controlla e restituisce se il campo è visibile o no
         *
         * @param   $tipo                               hidden, required
         * @param   $condizionamento_polimero           prima condizione: se false, restituisce false
         * @param   $tipo_condizionamento_polimero      fisso, mobile, fisso+mobile, fisso/mobile
         * @param   $subtipo_condizionamento_polimero   frigo, centralina
         *
         * @return true/false
        */

        $result = true;

        if ($condizionamento_polimero == false || $tipo_condizionamento_polimero !== Articolo::FISSOoMOBILE ) {
            $result = true;
        } else {
            //var_dump($subtipo_condizionamento_polimero);
            switch ($subtipo_condizionamento_polimero) {
                case Articolo::FRIGO :
                    $result = false;
                    break;
                case Articolo::CENTRALINA :
                    $result = true;
                    break;
                default:
                    $result = true;
                    break;
            }
        }
        if ($tipo == 'hidden') {
            $resrequired = $result;
        } else {
            $resrequired = ($result == true) ? false : true ;
        }
        return $resrequired;
    }

    public static function HiddenRequired_temperatura_fm($tipo,$condizionamento_polimero, $tipo_condizionamento_polimero, $subtipo_condizionamento_polimero): bool
    {
        /**
         * Controlla e restituisce se il campo è visibile o no
         *
         * @param   $tipo                               hidden, required
         * @param   $condizionamento_polimero           prima condizione: se false, restituisce false
         * @param   $tipo_condizionamento_polimero      fisso, mobile, fisso+mobile, fisso/mobile
         * @param   $subtipo_condizionamento_polimero   frigo, centralina
         *
         * @return true/false
        */

        $result = true;

        if ($condizionamento_polimero == false || $tipo_condizionamento_polimero !== Articolo::FISSOoMOBILE ) {
            $result = true;
        } else {
            //var_dump($subtipo_condizionamento_polimero);
            switch ($subtipo_condizionamento_polimero) {
                case Articolo::FRIGO :
                    $result = true;
                    break;
                case Articolo::CENTRALINA :
                    $result = false;
                    break;
                default:
                    $result = true;
                    break;
            }
        }
        if ($tipo == 'hidden') {
            $resrequired = $result;
        } else {
            $resrequired = ($result == true) ? false : true ;
        }
        return $resrequired;
    }
}
