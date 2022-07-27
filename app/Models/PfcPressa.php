<?php

namespace App\Models;

use App\Models\Pfc;
use App\Models\Attrezzature\Pressa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PfcPressa extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'pfc_pressa';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'pfc_id',
        'pressa_id',
        'serve_robot',
        'stampaggio_automatico',
    ];

    public function pfc(): BelongsToMany
    {
        return $this->belongsToMany(Pfc::class, 'pfc_id');
    }

    public function presse(): BelongsToMany
    {
        return $this->belongsToMany(Pressa::class, 'pressa_id');
    }
}
