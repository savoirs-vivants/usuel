<?php

// app/Models/Passation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passation extends Model
{
    protected $fillable = [
        'id_beneficiaire',
        'id_travailleur',
        'score',
        'scenario',
        'modules',
        'date',
        'consentement_recherche',
        'mode_ordre',
    ];

    protected $casts = [
        'score'                  => 'array',
        'consentement_recherche' => 'boolean',
        'date'                   => 'date',
        'created_at' => 'datetime',
    ];


    public function beneficiaire()
    {
        return $this->belongsTo(Beneficiaire::class, 'id_beneficiaire');
    }


    public function getScoreTotalAttribute(): float
    {
        return round(array_sum($this->score ?? []), 2);
    }

    public function getScoreCategorie(string $categorie): float
    {
        return (float) ($this->score[$categorie] ?? 0.0);
    }
}
