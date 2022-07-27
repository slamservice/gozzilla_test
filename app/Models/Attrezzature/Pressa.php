<?php

namespace App\Models\Attrezzature;

use App\Models\Intervento;
use App\Models\PfcmadrePressa;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Anagrafiche\Fornitore;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pressa extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'codice',
        'descrizione',
        'tonnellaggio',
        'fornitore_id',
        'matricola',
        'diametro_vite',
        'grammatura_stampaggio',
        'passaggio_colonne_altezza',
        'passaggio_colonne_larghezza',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'presse';

    public function codiceDescrizione(): Attribute
    {
        return Attribute::get(fn() => $this->codice.' - '.$this->descrizione);
    }

    public function fornitore()
    {
        return $this->belongsTo(Fornitore::class);
    }

    public function pfcmadre(): HasMany
    {
        return $this->hasMany(PfcmadrePressa::class, 'pressa_id');
    }

    public function interventi(): HasMany
    {
        return $this->hasMany(Intervento::class, 'elemento_id')->where('elemento', '=', Intervento::PRESSA);
    }
}
