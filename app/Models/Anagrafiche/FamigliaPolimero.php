<?php

namespace App\Models\Anagrafiche;

use App\Models\Anagrafiche\Articolo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FamigliaPolimero extends Model
{
    use HasFactory;

    protected $fillable = ['sigla', 'descrizione'];

    protected $searchableFields = ['*'];

    protected $table = 'famiglie_polimero';

    public function siglaDescrizione(): Attribute
    {
        return Attribute::get(fn() => $this->sigla.' - '.$this->descrizione
        );
    }

    public function articoli()
    {
        return $this->hasMany(Articolo::class);
    }
}
