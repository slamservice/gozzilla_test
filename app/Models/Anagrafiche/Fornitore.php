<?php

namespace App\Models\Anagrafiche;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Fornitore extends Model
{
    use HasFactory;

    protected $fillable = ['codice', 'nome'];

    protected $searchableFields = ['*'];

    protected $table = 'fornitori';


    public function codiceNome(): Attribute
    {
        return Attribute::get(fn() => $this->codice.' - '.$this->nome
        );
    }

    public function polimeri()
    {
        return $this->hasMany(Polimero::class);
    }

    public function masters()
    {
        return $this->hasMany(Master::class);
    }

    public function presse()
    {
        return $this->hasMany(Pressa::class);
    }
}
