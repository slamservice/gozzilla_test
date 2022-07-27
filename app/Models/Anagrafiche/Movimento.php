<?php

namespace App\Models\Anagrafiche;

use Illuminate\Support\Facades\DB;
use App\Models\Anagrafiche\Articolo;
use App\Models\Anagrafiche\Magazzino;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movimento extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $searchableFields = ['*'];

        /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'movimenti';

    const CARICO = "carico";
    const SCARICO = "scarico";
    public static function getTipoMovimento()
    {
        /**
        * @return array<value, label>
        */
        return [
            Movimento::CARICO => Movimento::CARICO,
            Movimento::SCARICO => Movimento::SCARICO
        ];
    }

    public static function getColoriTipoMovimento()
    {
        /**
        * @return array<value, label>
        */
        return [
            'success' => Movimento::CARICO,
            'danger' => Movimento::SCARICO
        ];
    }

    public function magazzino()
    {
        return $this->belongsTo(Magazzino::class, 'magazzino_id' );
    }

    public function articolo()
    {
        return $this->belongsTo(Articolo::class, 'articolo_id');
    }

    public function lotto():HasOne
    {
        return $this->HasOne(Lotto::class, 'id', 'lotto_id');
    }

    public function lotti()
    {
        return $this->belongsTo(Lotto::class, 'lotto_id');
    }

    public static function lottiArticolo($articolo_id)
    {
        $esistenza = Lotto::select(DB::raw("id as id,
        lotto_dataLotto as lotto_dataLotto"))
        ->where('lotti.articolo_id','=',$articolo_id)
        ->orderBy('lotti.lotto')
        ->orderBy('lotti.lotto_dataLotto')
        ->get()->pluck('lotto_dataLotto', 'id');

        return $esistenza;
    }
}
