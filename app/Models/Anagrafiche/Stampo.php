<?php

namespace App\Models\Anagrafiche;

use App\Models\Intervento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stampo extends Model
{
    use HasFactory;

    protected $fillable = [
        'codice',
        'descrizione',
        'tipologia',
        'allestimento',
        'disallestimento',
        'ubicazione',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'stampi';

    const FRIGO = 'frigo';
    const CENTRALINA = 'centralina';
    public static function getSubTipoCondizionamentoStampo()
    {
        /**
        * @return array<value, label>
        */
        return [
            Stampo::FRIGO => Stampo::FRIGO,
            Stampo::CENTRALINA => Stampo::CENTRALINA
        ];
    }

    const FISSO = "fisso";
    const MOBILE = "mobile";
    const FISSOeMOBILE = "fisso+mobile";
    const FISSOoMOBILE = "fisso/mobile";
    public static function getTipoCondizionamentoStampo()
    {
        /**
        * @return array<value, label>
        */
        return [
            Stampo::FISSO => Stampo::FISSO,
            Stampo::MOBILE => Stampo::MOBILE,
            Stampo::FISSOeMOBILE => Stampo::FISSOeMOBILE,
            Stampo::FISSOoMOBILE => Stampo::FISSOoMOBILE
        ];
    }
    public function allPfcmadre()
    {
        return $this->hasMany(Pfcmadre::class);
    }

    public function codiceDescrizione(): Attribute
    {
        return Attribute::get(fn() => $this->codice.' - '.$this->descrizione
        );
    }

    public function interventi(): HasMany
    {
        return $this->hasMany(Intervento::class, 'elemento_id')->where('elemento', '=', Intervento::STAMPO);
    }

    public static function HiddenRequired_numero_linee($tipo,$condizionamento_stampo, $subtipo_condizionamento_stampo): bool
    {
        /**
         * Controlla e restituisce se il campo è visibile o no
         *
         * @param   $tipo                               hidden, required
         * @param   $condizionamento_stampo           prima condizione: se false, restituisce false
         * @param   $tipo_condizionamento_stampo      fisso, mobile, fisso+mobile, fisso/mobile
         * @param   $subtipo_condizionamento_stampo   frigo, centralina
         *
         * @return true/false
        */

        $result = true;

        if ($condizionamento_stampo == false) {
            $result = true;
        } else {
            //var_dump($subtipo_condizionamento_stampo);
            switch ($subtipo_condizionamento_stampo) {
                case Stampo::FRIGO :
                    $result = false;
                    break;
                case Stampo::CENTRALINA :
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

    public static function HiddenRequired_temperatura($tipo,$condizionamento_stampo, $subtipo_condizionamento_stampo): bool
    {
        /**
         * Controlla e restituisce se il campo è visibile o no
         *
         * @param   $tipo                               hidden, required
         * @param   $condizionamento_stampo           prima condizione: se false, restituisce false
         * @param   $tipo_condizionamento_stampo      fisso, mobile, fisso+mobile, fisso/mobile
         * @param   $subtipo_condizionamento_stampo   frigo, centralina
         *
         * @return true/false
        */

        $result = true;

        if ($condizionamento_stampo == false) {
            $result = true;
        } else {
            //var_dump($subtipo_condizionamento_stampo);
            switch ($subtipo_condizionamento_stampo) {
                case Stampo::FRIGO :
                    $result = true;
                    break;
                case Stampo::CENTRALINA :
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

    public static function HiddenRequired_numero_linee_fm($tipo,$condizionamento_stampo, $tipo_condizionamento_stampo, $subtipo_condizionamento_stampo): bool
    {
        /**
         * Controlla e restituisce se il campo è visibile o no
         *
         * @param   $tipo                               hidden, required
         * @param   $condizionamento_stampo           prima condizione: se false, restituisce false
         * @param   $tipo_condizionamento_stampo      fisso, mobile, fisso+mobile, fisso/mobile
         * @param   $subtipo_condizionamento_stampo   frigo, centralina
         *
         * @return true/false
        */

        $result = true;

        if ($condizionamento_stampo == false || $tipo_condizionamento_stampo !== Stampo::FISSOoMOBILE ) {
            $result = true;
        } else {
            //var_dump($subtipo_condizionamento_stampo);
            switch ($subtipo_condizionamento_stampo) {
                case Stampo::FRIGO :
                    $result = false;
                    break;
                case Stampo::CENTRALINA :
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

    public static function HiddenRequired_temperatura_fm($tipo,$condizionamento_stampo, $tipo_condizionamento_stampo, $subtipo_condizionamento_stampo): bool
    {
        /**
         * Controlla e restituisce se il campo è visibile o no
         *
         * @param   $tipo                               hidden, required
         * @param   $condizionamento_stampo           prima condizione: se false, restituisce false
         * @param   $tipo_condizionamento_stampo      fisso, mobile, fisso+mobile, fisso/mobile
         * @param   $subtipo_condizionamento_stampo   frigo, centralina
         *
         * @return true/false
        */

        $result = true;

        if ($condizionamento_stampo == false || $tipo_condizionamento_stampo !== Stampo::FISSOoMOBILE ) {
            $result = true;
        } else {
            //var_dump($subtipo_condizionamento_stampo);
            switch ($subtipo_condizionamento_stampo) {
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
