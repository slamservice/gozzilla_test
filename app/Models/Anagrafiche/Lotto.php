<?php

namespace App\Models\Anagrafiche;

use DateTimeInterface;
use App\Models\Anagrafiche\Articolo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lotto extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $searchableFields = ['*'];

        /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lotti';

    protected $casts = [
        'data_lotto' => 'date',
    ];



    public function lottoData(): Attribute
    {
        return Attribute::get(fn() => $this->lotto.' - '.date_format($this->data_lotto, "d/m/Y"));
    }

    public function movimenti(): HasMany
    {
        return $this->hasMany(Movimento::class, 'articolo_id');
    }

    // public function articoli(): HasMany
    // {
    //     return $this->hasMany(Articolo::class, 'articolo_id');
    // }
    public function articoli()
    {
        return $this->belongsTo(Articolo::class, 'articolo_id');
    }
}
