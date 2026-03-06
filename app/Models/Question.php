<?php

// app/Models/Question.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['intitule', 'image', 'categorie', 'reponse_correcte', 'choix', 'active'];

    protected $casts = [
        'choix' => 'array',
        'active' => 'boolean',
    ];

    public function getChoixSansEAttribute(): array
    {
        return array_filter($this->choix, fn($k) => $k !== 'E', ARRAY_FILTER_USE_KEY);
    }

    public function getPoids(string $reponse): float
    {
        return (float) ($this->choix[$reponse]['poids'] ?? 0.0);
    }
}
