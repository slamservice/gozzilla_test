<?php

namespace App\Models;

use App\Models\Pfc;
use App\Models\Anagrafiche\Articolo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PfcDataConsegna extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'pfc_date_consegna';

    protected $casts = [
        'data_consegna' => 'date',
    ];
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'pfc_articolo_id',
        'data_consegna',
        'qta',
    ];

    public function articolo()
    {
        return $this->belongsTo(PfcArticolo::class);
    }

}
