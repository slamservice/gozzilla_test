<?php

namespace App\Models;

use App\Models\PfcmadreMaster;
use App\Models\PfcmadreImballo;
use App\Models\PfcmadreInserto;
use App\Models\PfcmadreStampato;
use App\Models\Anagrafiche\Stampo;
use App\Models\Anagrafiche\Cliente;
use App\Models\Attrezzature\Pressa;
use App\Models\Anagrafiche\Articolo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pfcmadre extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $searchableFields = ['*'];

    protected $table = 'pfcmadre';

    const NERO = "nero";
    const TRASPARENTE = "trasparente";
    const COLORATO = "colorato";
    public static function getColore()
    {
        /**
        * @return array<value, label>
        */
        return [
            Pfcmadre::NERO => Pfcmadre::NERO,
            Pfcmadre::TRASPARENTE => Pfcmadre::TRASPARENTE,
            Pfcmadre::COLORATO => Pfcmadre::COLORATO,
        ];
    }

    protected $casts = [
        'stampo_condizionamento' => 'boolean',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function stampo()
    {
        return $this->belongsTo(Stampo::class);
    }

    public function articoli(): HasMany
    {
        return $this->hasMany(PfcmadreArticolo::class, 'pfcmadre_id');
    }

    public function polimero()
    {
        return $this->belongsTo(Polimero::class);
    }

    public function masters(): HasMany
    {
        return $this->hasMany(PfcmadreMaster::class, 'pfcmadre_id');
    }

    public function stampati(): HasMany
    {
        return $this->hasMany(PfcmadreStampato::class, 'pfcmadre_id');
    }

    public function imballi(): HasMany
    {
        return $this->hasMany(PfcmadreImballo::class, 'pfcmadre_id');
    }

    public function inserti(): HasMany
    {
        return $this->hasMany(PfcmadreInserto::class, 'pfcmadre_id');
    }

    public function presse(): HasMany
    {
        return $this->hasMany(PfcmadrePressa::class, 'pfcmadre_id');
    }

}
