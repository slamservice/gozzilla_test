<?php

namespace App\Models;

use App\Models\Pfcmadre;
use App\Models\Contatore;
use App\Models\PfcArticolo;
use App\Models\Anagrafiche\Stampo;
use App\Models\Anagrafiche\Cliente;
use App\Models\Anagrafiche\Articolo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Pfc extends Model
{
    use HasFactory;


    protected $guarded = ['id'];


    protected $searchableFields = ['*'];

    protected $table = 'pfc';



    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function pfcmadre()
    {
        return $this->belongsTo(Pfcmadre::class);
    }

    public function articoli(): HasMany
    {
        return $this->hasMany(PfcArticolo::class, 'pfc_id');
    }

    public function stampo()
    {
        return $this->belongsTo(Stampo::class);
    }

    public function polimero()
    {
        return $this->belongsTo(Polimero::class);
    }

    public function masters(): HasMany
    {
        return $this->hasMany(PfcMaster::class, 'pfc_id');
    }

    public function stampati(): HasMany
    {
        return $this->hasMany(PfcStampato::class, 'pfc_id');
    }

    public function imballi(): HasMany
    {
        return $this->hasMany(PfcImballo::class, 'pfc_id');
    }

    public function inserti(): HasMany
    {
        return $this->hasMany(PfcInserto::class, 'pfc_id');
    }

    public function presse(): HasMany
    {
        return $this->hasMany(PfcPressa::class, 'pfc_id');
    }

}
