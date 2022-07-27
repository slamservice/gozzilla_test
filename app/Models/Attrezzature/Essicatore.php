<?php

namespace App\Models\Attrezzature;

use App\Models\Intervento;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Anagrafiche\Fornitore;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Essicatore extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'codice',
        'descrizione',
        'fornitore_id',
        'matricola',
        'portata',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'essicatori';

    public function fornitore()
    {
        return $this->belongsTo(Fornitore::class);
    }

    public function allPfcmadre()
    {
        return $this->belongsToMany(Pfcmadre::class);
    }

    public function interventi(): HasMany
    {
        return $this->hasMany(Intervento::class, 'elemento_id')->where('elemento', '=', Intervento::ESSICATORE);
    }
}
