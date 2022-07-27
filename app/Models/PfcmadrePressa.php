<?php

namespace App\Models;

use App\Models\Pfcmadre;
use App\Models\Attrezzature\Pressa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PfcmadrePressa extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'pfcmadre_pressa';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'pfcmadre_id',
        'pressa_id',
        'serve_robot',
        'stampaggio_automatico',
    ];

    public function pfcmadre(): BelongsToMany
    {
        return $this->belongsToMany(Pfcmadre::class, 'pfcmadre_id');
    }

    public function presse(): BelongsToMany
    {
        return $this->belongsToMany(Pressa::class, 'pressa_id');
    }
}
