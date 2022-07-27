<?php

namespace App\Models;

use App\Models\Pfc;
use App\Models\Anagrafiche\Articolo;
use App\Models\Anagrafiche\Movimento;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PfcArticolo extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'pfc_articolo';

    protected $guarded = ['id'];

    // /**
    //  * @var array<int, string>
    //  */
    // protected $fillable = [
    //     'pfc_id',
    //     'articolo_id',
    //     'numero_impronte',
    //     'peso_impronte',
    //     'subtotale',
    // ];


    public function movimenti(): HasMany
    {
        return $this->hasMany(Movimento::class, 'articolo_id');
    }

    public function pfc(): BelongsToMany
    {
        return $this->belongsToMany(Pfc::class, 'pfc_id');
    }

    public function articoli(): BelongsToMany
    {
        return $this->belongsToMany(Articolo::class, 'articolo_id');
    }

    public function dateConsegna(): HasMany
    {
        return $this->HasMany(PfcDataConsegna::class, 'pfc_articolo_id');
    }

}
