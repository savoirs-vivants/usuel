<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficiaire extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'genre',
        'age',
        'diplome',
        'csp',
    ];

    public function passations()
    {
        return $this->hasMany(Passation::class, 'id_beneficiaire');
    }
}
