<?php

namespace App\Models\Anagrafiche;

use App\Models\Anagrafiche\Movimento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Magazzino extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $searchableFields = ['*'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'magazzini';

    protected $dates = [
        'attivato_il',
        'disattivato_il',
    ];

    public function codiceDescrizione(): Attribute
    {
        return Attribute::get(fn() => $this->codice.' - '.$this->descrizione);
    }

    public function scopeActive($query)
    {
        // $active_administrators = Magazzino::active()->get();
        return $query->where('attivo','=',1);
    }


    public function movimenti(): HasMany
    {
        return $this->hasMany(Movimento::class);
    }

    public function articoli(): HasMany
    {
        return $this->hasMany(Movimento::class);
    }
}
