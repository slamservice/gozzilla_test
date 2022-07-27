<?php

namespace App\Models\Anagrafiche;

use App\Models\Pfcmadre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['codice', 'nome'];

    protected $searchableFields = ['*'];

    protected $table = 'clienti';


    public function codiceNome(): Attribute
    {
        return Attribute::get(fn() => $this->codice.' - '.$this->nome);
    }

    public function articoli()
    {
        return $this->hasMany(Articolo::class);
    }

    public function inserti()
    {
        return $this->hasMany(Inserto::class);
    }

    public function pfcmadre()
    {
        return $this->hasMany(Pfcmadre::class);
    }
}
