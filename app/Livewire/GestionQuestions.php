<?php

namespace App\Livewire;

use App\Models\Question;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class GestionQuestions extends Component
{
    use WithFileUploads;

    public array  $questions        = [];
    public ?int   $selectedId       = null;
    public bool   $isNew            = false;

    public string $intitule         = '';
    public string $categorie        = '';
    public string $existingImage    = '';
    public        $newImage         = null;
    public bool   $removeImage      = false;

    public int    $nbReponses       = 4;
    public array  $choix            = [];

    public bool   $saved            = false;
    public string $savedMessage     = '';

    public bool $showPreview = false;

    public function previsualiser(): void
    {
        $this->showPreview = true;
    }

    public function fermerPrevisualisation(): void
    {
        $this->showPreview = false;
    }

    public array $categories = [
        'Resilience' => 'Résilience',
        'EC'         => 'Esprit Critique',
        'CSDLEN'     => 'Comportements Sociaux',
        'CT'         => 'Compétence Technique',
        'TDLinfo'    => "Traitement de l'Information",
        'CDC'        => 'Création de Contenu',
    ];

    public array $poidsOptions = [
        '1'    => '+1 — Bonne réponse',
        '0.5'  => '+0.5 — Partielle',
        '0'    => '0 — Neutre',
        '-0.5' => '−0.5 — Partiellement mauvaise',
        '-1'   => '−1 — Mauvaise',
    ];

    public function mount(): void
    {
        $this->loadQuestions();

        if (!empty($this->questions)) {
            $this->selectQuestion($this->questions[0]['id']);
        }
    }

    private function loadQuestions(): void
    {
        $this->questions = Question::orderBy('id')
            ->get(['id', 'intitule', 'categorie', 'active'])
            ->map(fn($q) => [
                'id'        => $q->id,
                'intitule'  => $q->intitule ?? '',
                'categorie' => $q->categorie ?? '',
                'active'    => (bool) $q->active,
            ])
            ->toArray();
    }

    public function toggleActive(int $id): void
    {
        $q = Question::findOrFail($id);
        $q->update(['active' => !$q->active]);
        $this->loadQuestions();
    }

    public function selectQuestion(int $id): void
    {
        $q = Question::findOrFail($id);

        $this->selectedId     = $id;
        $this->isNew          = false;
        $this->intitule       = $q->intitule    ?? '';
        $this->categorie      = $q->categorie   ?? '';
        $this->existingImage  = $q->image       ?? '';
        $this->newImage       = null;
        $this->removeImage    = false;
        $this->saved          = false;

        $raw = $q->choix;
        if (is_string($raw)) {
            $raw = json_decode($raw, true);
        } else {
            $raw = json_decode(json_encode($raw), true);
        }

        if (!is_array($raw)) {
            $raw = [];
        }

        $parsed = [];
        foreach ($raw as $lettre => $data) {
            if ($lettre === 'E') continue;

            $texte = $data['texte'] ?? '';

            $poidsFloat = floatval($data['poids'] ?? 0);

            $poidsStr = '0';
            if ($poidsFloat == 1) $poidsStr = '1';
            elseif ($poidsFloat == 0.5) $poidsStr = '0.5';
            elseif ($poidsFloat == -0.5) $poidsStr = '-0.5';
            elseif ($poidsFloat == -1) $poidsStr = '-1';

            $parsed[] = [
                'texte' => $texte,
                'poids' => $poidsStr,
            ];
        }

        if (empty($parsed)) {
            $parsed = array_fill(0, 4, ['texte' => '', 'poids' => '0']);
        }

        $this->choix = $parsed;
        $this->nbReponses = max(2, count($this->choix));
    }

    public function nouvelleQuestion(): void
    {
        $this->selectedId    = null;
        $this->isNew         = true;
        $this->intitule      = '';
        $this->categorie     = array_key_first($this->categories);
        $this->existingImage = '';
        $this->newImage      = null;
        $this->removeImage   = false;
        $this->nbReponses    = 4;
        $this->saved         = false;

        $this->choix = array_fill(0, 4, ['texte' => '', 'poids' => '0']);
    }

    public function updatedNbReponses(int $value): void
    {
        $this->nbReponses = max(2, min(8, $value));
        $this->syncNbReponses();
    }

    private function syncNbReponses(): void
    {
        $current = count($this->choix);
        $target  = $this->nbReponses;

        if ($target > $current) {
            for ($i = $current; $i < $target; $i++) {
                $this->choix[] = ['texte' => '', 'poids' => '0'];
            }
        } elseif ($target < $current) {
            $this->choix = array_slice($this->choix, 0, $target);
        }
    }

    public function toggleCorrect(int $index): void
    {
        if (($this->choix[$index]['poids'] ?? '0') === '1') {
            $this->choix[$index]['poids'] = '0';
        } else {
            $this->choix[$index]['poids'] = '1';
        }
    }

    public function supprimerImage(): void
    {
        $this->removeImage   = true;
        $this->existingImage = '';
        $this->newImage      = null;
    }

    public function sauvegarder(): void
    {
        $this->validate([
            'intitule'            => 'required|string|min:5|max:500',
            'categorie'           => 'required|in:' . implode(',', array_keys($this->categories)),
            'newImage'            => 'nullable|image|max:2048',
            'choix'               => 'required|array|min:2',
            'choix.*.texte'      => 'required|string|min:1|max:300',
            'choix.*.poids'      => 'required|in:1,0.5,0,-0.5,-1',
        ], [
            'intitule.required'          => "L'intitulé est obligatoire.",
            'intitule.min'               => "L'intitulé doit faire au moins 5 caractères.",
            'categorie.required'         => 'La catégorie est obligatoire.',
            'choix.*.texte.required'  => 'Chaque choix doit avoir un texte.',
            'choix.*.poids.required'  => 'Chaque choix doit avoir un poids.',
        ]);

        $imagePath = $this->existingImage;

        if ($this->removeImage) {
            if ($imagePath) Storage::disk('public')->delete($imagePath);
            $imagePath = null;
        }

        if ($this->newImage) {
            if ($imagePath) Storage::disk('public')->delete($imagePath);
            $imagePath = $this->newImage->store('questions', 'public');
        }

        $letters  = ['A', 'B', 'C', 'D', 'F', 'G', 'H'];
        $choix = [];
        $lettreCorrecte = 'A';

        foreach (array_values($this->choix) as $idx => $r) {
            $lettre = $letters[$idx];
            $poids = (float) $r['poids'];

            $choix[$lettre] = [
                'texte' => trim($r['texte']),
                'poids' => $poids,
            ];

            if ($poids == 1) {
                $lettreCorrecte = $lettre;
            }
        }

        $choix['E'] = ['texte' => 'Je ne sais pas', 'poids' => 0.0];

        $data = [
            'intitule'         => trim($this->intitule),
            'categorie'        => $this->categorie,
            'image'            => $imagePath,
            'choix'            => $choix,
            'reponse_correcte' => $lettreCorrecte,
        ];

        if ($this->isNew) {
            $q = Question::create($data);
            $this->selectedId = $q->id;
            $this->isNew      = false;
            $this->savedMessage = 'Question créée avec succès.';
        } else {
            Question::findOrFail($this->selectedId)->update($data);
            $this->savedMessage = 'Modifications enregistrées.';
        }

        $this->saved         = true;
        $this->existingImage = $imagePath ?? '';
        $this->newImage      = null;

        $this->loadQuestions();
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.gestion-questions');
    }
}
