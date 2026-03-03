<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tracking extends Model
{
    protected $table = 'tracking';

    public $timestamps = false;

    protected $fillable = [
        'id_passation',
        'id_question',
        'position',
        'temps_total_ms',
        'latence_ms',
        'nb_clics',
        'nb_changements',
        'nb_clics_hors_cible',
        'nb_pauses',
        'suivi_souris',
        'resultat',
    ];

    protected $casts = [
        'temps_total_ms'     => 'float',
        'latence_ms'         => 'float',
        'nb_clics'           => 'integer',
        'nb_changements'     => 'integer',
        'nb_clics_hors_cible'=> 'integer',
        'nb_pauses'          => 'integer',
        'resultat' => 'float',
    ];

    public function passation(): BelongsTo
    {
        return $this->belongsTo(Passation::class, 'id_passation');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'id_question');
    }

    public function getSuiviSourisArrayAttribute(): array
    {
        return $this->suivi_souris ? json_decode($this->suivi_souris, true) : [];
    }
}
