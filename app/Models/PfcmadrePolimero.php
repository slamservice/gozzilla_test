<?php

namespace App\Models;

use App\Models\Pfcmadre;
use App\Models\Anagrafiche\Articolo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PfcmadrePolimero extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'pfcmadre_polimero';

    public $timestamps = false;

    protected $guarded = [];

    public function pfcmadre(): BelongsToMany
    {
        return $this->belongsToMany(Pfcmadre::class, 'pfcmadre_id');
    }

    public function polimeri(): BelongsToMany
    {
        return $this->belongsToMany(Articolo::class, 'articolo_polimero_id');
    }
}
