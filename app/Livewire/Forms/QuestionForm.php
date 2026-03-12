<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Question;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class QuestionForm extends Form
{
    public ?int $selectedId = null;
    public bool $isNew = false;

    public string $intitule = '';
    public string $categorie = '';
    public string $existingImage = '';

    /** @var TemporaryUploadedFile|null */
    public $newImage = null;
    public bool $removeImage = false;

    public int $nbReponses = 4;
    public array $choix = [];

    public array $categories = [
        'Resilience' => 'Résilience',
        'EC'         => 'Esprit Critique',
        'CSDLEN'     => 'Comportements Sociaux',
        'CT'         => 'Compétence Technique',
        'TDLinfo'    => "Traitement de l'Information",
        'CDC'        => 'Création de Contenu',
    ];

    /**
     * Règles de validation.
     */
    protected function rules(): array
    {
        return [
            'intitule'         => 'required|string|min:5|max:500',
            'categorie'        => 'required|in:' . implode(',', array_keys($this->categories)),
            'newImage'         => 'nullable|image|max:2048',
            'choix'            => 'required|array|min:2',
            'choix.*.texte'    => 'required|string|min:1|max:300',
            'choix.*.poids'    => 'required|in:1,0.5,0,-0.5,-1',
        ];
    }

    protected function messages(): array
    {
        return [
            'intitule.required'      => "L'intitulé est obligatoire.",
            'intitule.min'           => "L'intitulé doit faire au moins 5 caractères.",
            'categorie.required'     => 'La catégorie est obligatoire.',
            'choix.*.texte.required' => 'Chaque choix doit avoir un texte.',
            'choix.*.poids.required' => 'Chaque choix doit avoir un poids.',
            'newImage.image'         => 'Le fichier doit être une image.',
            'newImage.max'           => 'L\'image ne doit pas dépasser 2 Mo.',
        ];
    }

    /**
     * Charge une question existante dans le formulaire.
     */
    public function loadQuestion(Question $q): void
    {
        $this->selectedId    = $q->id;
        $this->isNew         = false;
        $this->intitule      = $q->intitule ?? '';
        $this->categorie     = $q->categorie ?? '';
        $this->existingImage = $q->image ?? '';
        $this->newImage      = null;
        $this->removeImage   = false;

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

    /**
     * Initialise le formulaire pour une nouvelle question.
     */
    public function resetForNew(): void
    {
        $this->reset();
        $this->isNew = true;
        $this->categorie = array_key_first($this->categories);
        $this->choix = array_fill(0, 4, ['texte' => '', 'poids' => '0']);
    }

    /**
     * Sauvegarde la question (création ou mise à jour).
     */
    public function save(): array
    {
        $this->validate();

        $imagePath = $this->existingImage;

        if ($this->removeImage) {
            if ($imagePath) Storage::disk('public')->delete($imagePath);
            $imagePath = null;
        }

        if ($this->newImage) {
            if ($imagePath) Storage::disk('public')->delete($imagePath);
            $imagePath = $this->newImage->store('questions', 'public');
        }

        $letters = ['A', 'B', 'C', 'D', 'F', 'G', 'H'];
        $choixFormates = [];
        $lettreCorrecte = 'A';

        foreach (array_values($this->choix) as $idx => $r) {
            $lettre = $letters[$idx];
            $poids = (float) $r['poids'];

            $choixFormates[$lettre] = [
                'texte' => trim($r['texte']),
                'poids' => $poids,
            ];

            if ($poids == 1) {
                $lettreCorrecte = $lettre;
            }
        }

        $choixFormates['E'] = ['texte' => 'Je ne sais pas', 'poids' => 0.0];

        $data = [
            'intitule'         => trim($this->intitule),
            'categorie'        => $this->categorie,
            'image'            => $imagePath,
            'choix'            => $choixFormates,
            'reponse_correcte' => $lettreCorrecte,
        ];

        if ($this->isNew) {
            $maxId = Question::max('id') ?? 0;
            $q = Question::create($data);
            $q->id = $maxId + 1;
            $q->save();
            $this->selectedId = $q->id;
            $this->isNew = false;
            $message = 'Question créée avec succès.';
        } else {
            Question::findOrFail($this->selectedId)->update($data);
            $message = 'Modifications enregistrées.';
        }

        $this->existingImage = $imagePath ?? '';
        $this->newImage = null;
        $this->removeImage = false;

        return ['id' => $this->selectedId, 'message' => $message];
    }
}
