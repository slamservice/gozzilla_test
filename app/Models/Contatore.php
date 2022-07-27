<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contatore extends Model
{
    use HasFactory;

    protected $table = 'contatori';

    protected $fillable = [
        'tipo',
        'maschera',
        'valore',
        'cambio_anno',
        'anno'
    ];
}
