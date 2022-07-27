<?php

namespace App\Models;

use App\Models\Pfc;
use App\Models\Anagrafiche\Articolo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PfcMaster extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'pfc_master';

    protected $guarded = [];

    public function pfc(): BelongsToMany
    {
        return $this->belongsToMany(Pfcmadre::class, 'pfc_id');
    }

    public function masters(): BelongsToMany
    {
        return $this->belongsToMany(Articolo::class, 'articolo_master_id');
    }
}
