<?php

namespace App\Models;

use App\Models\Anagrafiche\Articolo;
use App\Models\Anagrafiche\Lotto;
use App\Models\Anagrafiche\Movimento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Giacenza extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $searchableFields = ['*'];

    const CARICO = "carico";
    const SCARICO = "scarico";

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'movimenti';
    // public static function getTipoMovimento()
    // {
    //     /**
    //     * @return array<value, label>
    //     */
    //     return [
    //         Movimento::CARICO => Movimento::CARICO,
    //         Movimento::SCARICO => Movimento::SCARICO
    //     ];
    // }

    // public static function getColoriTipoMovimento()
    // {
    //     /**
    //     * @return array<value, label>
    //     */
    //     return [
    //         'success' => Movimento::CARICO,
    //         'danger' => Movimento::SCARICO
    //     ];
    // }




    public function lotti()
    {
        return $this->belongsToMany(Lotto::class, 'movimenti', 'lotto_id');
    }

    public function movimenti()
    {
        return $this->belongsToMany(Movimento::class);
    }

    public function articolo()
    {
        return $this->belongsTo(Articolo::class, 'articolo_id');
    }

     // public function codiceDescrizione(): Attribute
    // {
    //     return Attribute::get(fn() => $this->codice.' - '.$this->descrizione);
    // }

    // public function movimenti(): HasMany
    // {
    //     return $this->hasMany(Movimento::class, 'articolo_id');
    // }
    // public static function getEsistenze():Builder
    // {
    //     $esistenza = Esistenza::select(DB::raw("magazzino_id as id,
    //     concat(magazzini.codice,' ',magazzini.descrizione) as magazzino,
    //     concat(articoli.codice,' ',articoli.descrizione) as articolo,
    //     sum(movimenti.qta_carico - movimenti.qta_scarico) as esistenza"))
    //     ->leftjoin('magazzini','magazzini.id','=','movimenti.magazzino_id')
    //     ->leftjoin('articoli','articoli.id','=','movimenti.articolo_id')
    //     ->groupBy('magazzino_id')
    //     ->groupBy('articolo_id');
    //     // ->orderBy('magazzini.codice')
    //     // ->orderBy('articoli.codice');
    //     return $esistenza;
    // }

    // public function scopeActive($query)
    // {
    //     // $active_administrators = Magazzino::active()->get();
    //     return $query->where('attivo','=',1);
    // }

    // public function movimenti(): HasMany
    // {
    //     return $this->hasMany(Movimento::class, 'magazzino_id');
    // }

    // public function articoli()
    // {
    //     return $this->belongsToMany(Articolo::class,'movimenti','magazzino_id')->withPivot('articolo_id','qta_carico');
    // }
}
