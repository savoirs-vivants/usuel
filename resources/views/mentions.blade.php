@extends('layouts.app')

@section('title', 'Usuel - Mentions Légales')

@section('content')
<section class="bg-gray-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto relative z-10">

        <div class="mb-10 text-center relative">
            <a href="javascript:history.back()" class="absolute left-0 top-1/2 -translate-y-1/2 text-gray-400 hover:text-sv-blue transition-colors p-2 bg-white rounded-full shadow-sm hover:shadow-md border border-gray-100 hidden md:flex">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h1 class="font-sans font-bold text-3xl sm:text-4xl text-sv-blue tracking-tight">Mentions Légales</h1>
            <p class="text-gray-500 mt-3 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
                Informations juridiques concernant l'application Usuel éditée par Savoirs Vivants.
            </p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden mb-10">
            <div class="bg-sv-blue/5 px-8 py-5 border-b border-sv-blue/10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-sv-blue flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                    </svg>
                </div>
                <h2 class="font-mono font-bold text-xl text-sv-blue">Identité et Hébergement</h2>
            </div>

            <div class="p-8 prose prose-sm sm:prose-base max-w-none text-gray-600">
                <h3 class="text-sv-blue font-bold text-lg mt-0">1. Éditeur du site</h3>
                <p>
                    Le présent site et l'application <strong>Usuel</strong> sont édités par l'association <strong>Savoirs Vivants</strong>.<br>
                    <strong>Forme juridique :</strong> Association loi 1901<br>
                    <strong>Siège social :</strong> [ADRESSE DE L'ASSO À COMPLÉTER]<br>
                    <strong>Numéro RNA / SIRET :</strong> [NUMÉRO À COMPLÉTER]<br>
                    <strong>Email de contact :</strong> <a href="mailto:contact@savoirsvivants.fr" class="text-sv-green font-bold hover:underline">contact@savoirsvivants.fr</a>
                </p>

                <h3 class="text-sv-blue font-bold text-lg">2. Directeur de la publication</h3>
                <p>
                    Le directeur de la publication est <strong>[NOM DU DIRECTEUR/PRÉSIDENT À COMPLÉTER]</strong>, en qualité de [Fonction].
                </p>

                <h3 class="text-sv-blue font-bold text-lg">3. Hébergement</h3>
                <p>
                    L'application Usuel est hébergée par <strong>[NOM DE L'HÉBERGEUR, ex: OVH]</strong>.<br>
                    <strong>Siège social :</strong> [ADRESSE DE L'HÉBERGEUR]<br>
                    <strong>Contact :</strong> [SITE WEB OU TEL DE L'HÉBERGEUR]
                </p>

                <h3 class="text-sv-blue font-bold text-lg">4. Propriété intellectuelle</h3>
                <p>
                    L'ensemble des éléments constituant l'application Usuel (textes, questionnaires, graphismes, logiciels, bases de données) sont la propriété exclusive de l'association Savoirs Vivants. Toute reproduction ou diffusion est interdite sans autorisation.
                </p>
            </div>
        </div>

    </div>
</section>
@endsection
