@extends('layouts.app')

@section('title', 'Usuel - Mentions Légales')

@section('content')
<section class="bg-gray-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8 font-grotesk">
    <div class="max-w-4xl mx-auto relative z-10">

        <div class="mb-10 text-center relative">
            <a href="javascript:history.back()" class="absolute left-0 top-1/2 -translate-y-1/2 text-gray-400 hover:text-sv-blue transition-colors p-2 bg-white rounded-full shadow-sm hover:shadow-md border border-gray-100 hidden md:flex" title="Retour">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h1 class="font-mono font-bold text-3xl sm:text-4xl text-sv-blue tracking-tight">Mentions Légales</h1>
            <p class="text-gray-500 mt-3 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
                Informations juridiques concernant l'application Usuel éditée par Savoirs Vivants.
            </p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden mb-10">

            <div class="bg-sv-blue/5 px-8 py-5 border-b border-sv-blue/10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-sv-blue flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h2 class="font-mono font-bold text-xl text-sv-blue">Informations générales</h2>
            </div>

            <div class="p-8 md:p-10 prose prose-sm sm:prose-base max-w-none text-gray-600">

                <h3 class="text-sv-blue font-bold text-lg mt-0">1. Éditeur de l'application</h3>
                <p>
                    L' application <strong>Usuel</strong> est éditée par l’association <strong>Savoirs Vivants</strong>.<br>
                    <strong>Forme juridique :</strong> Association de droit local<br>
                    <strong>Siège social :</strong> 30 rue du Maire André Traband, 67500 Haguenau<br>
                    <strong>Email de contact :</strong> <a href="mailto:contact@savoirsvivants.fr" class="text-sv-green font-bold hover:underline">contact@savoirsvivants.fr</a>
                </p>

                <hr class="border-gray-100 my-6">

                <h3 class="text-sv-blue font-bold text-lg">2. Responsable de la publication</h3>
                <p>
                    Le responsable de la publication est <strong>l’équipe de Savoirs Vivants</strong>.
                </p>

                <hr class="border-gray-100 my-6">

                <h3 class="text-sv-blue font-bold text-lg">3. Hébergement</h3>
                <p>
                    L'application et le site sont hébergés par <strong>Infomaniak</strong>, situé en <strong>Suisse</strong>.
                </p>

                <hr class="border-gray-100 my-6">

                <h3 class="text-sv-blue font-bold text-lg">4. Propriété intellectuelle</h3>
                <p>
                    Les contenus (textes, images, graphismes, logo, vidéos, documents téléchargeables, questionnaires) présents sur l'application sont la propriété exclusive de <strong>Savoirs Vivants</strong> ou de tiers ayant autorisé leur usage. Toute reproduction, distribution, modification ou publication, même partielle, est interdite sans l’accord écrit préalable de l’association.
                </p>

                <hr class="border-gray-100 my-6">

                <h3 class="text-sv-blue font-bold text-lg">5. Responsabilité</h3>
                <p>
                    <strong>Savoirs Vivants</strong> met tout en œuvre pour assurer l’exactitude et la mise à jour des informations figurant sur l'application. Cependant, l’association ne saurait être tenue responsable :
                </p>
                <ul class="list-disc pl-5 space-y-2 mt-2">
                    <li>Des erreurs, omissions ou résultats obtenus par un mauvais usage des informations du site.</li>
                    <li>De l’interruption ou de l’indisponibilité temporaire de l'application.</li>
                </ul>

                <hr class="border-gray-100 my-6">

                <h3 class="text-sv-blue font-bold text-lg">6. Données personnelles et Cookies</h3>
                <p>
                    <strong>Données personnelles :</strong> Le traitement des données personnelles est décrit dans la <a href="{{ route('confidentialite') }}" class="text-sv-green font-bold hover:underline">Politique de confidentialité</a>. Les données collectées (notamment via les formulaires) sont utilisées uniquement pour répondre à votre demande et pour les finalités prévues par l'application Usuel.<br><br>
                </p>

                <hr class="border-gray-100 my-6">

                <h3 class="text-sv-blue font-bold text-lg">7. Droit applicable</h3>
                <p>
                    La présente application est soumise au droit français. En cas de litige, les tribunaux français seront seuls compétents, sauf disposition légale contraire.
                </p>

            </div>
        </div>

    </div>
</section>
@endsection
