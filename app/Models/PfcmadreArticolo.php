<?php

namespace App\Models;

use App\Models\Pfcmadre;
use App\Models\Anagrafiche\Articolo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PfcmadreArticolo extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'pfcmadre_articolo';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'pfcmadre_id',
        'articolo_id',
        'numero_impronte',
        'peso_impronte',
    ];

    public function pfcmadre(): BelongsToMany
    {
        return $this->belongsToMany(Pfcmadre::class, 'pfcmadre_id');
    }

    public function articoli(): BelongsToMany
    {
        return $this->belongsToMany(Articolo::class, 'articolo_id');
    }
}
