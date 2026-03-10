@extends('layouts.app')

@section('title', 'Usuel - Politique de Confidentialité')

@section('content')
<section class="bg-gray-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto relative z-10">

        <div class="mb-10 text-center relative">
            <a href="javascript:history.back()" class="absolute left-0 top-1/2 -translate-y-1/2 text-gray-400 hover:text-sv-blue transition-colors p-2 bg-white rounded-full shadow-sm hover:shadow-md border border-gray-100 hidden md:flex">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h1 class="font-sans font-bold text-3xl sm:text-4xl text-sv-blue tracking-tight">Politique de Confidentialité</h1>
            <p class="text-gray-500 mt-3 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
                Protection de vos données personnelles (RGPD) et gestion des cookies.
            </p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden mb-10">
            <div class="bg-sv-green/10 px-8 py-5 border-b border-sv-green/20 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-sv-green flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h2 class="font-mono font-bold text-xl text-sv-blue">Données Personnelles et Cookies</h2>
            </div>

            <div class="p-8 prose prose-sm sm:prose-base max-w-none text-gray-600">
                <p class="lead font-medium text-gray-800">
                    L'association Savoirs Vivants s'engage à ce que les traitements de données effectués sur Usuel soient conformes au Règlement Général sur la Protection des Données (RGPD).
                </p>

                <h3 class="text-sv-blue font-bold text-lg mt-6">1. Données collectées</h3>
                <p>Nous collectons les données suivantes :</p>
                <ul class="space-y-2">
                    <li><strong>Professionnels :</strong> Identité, email, mot de passe haché, structure. <em>(Finalité : gestion des accès)</em>.</li>
                    <li><strong>Bénéficiaires :</strong> Identité, scores détaillés par module d'évaluation, date de passation.</li>
                    <li><strong>Recherche :</strong> Sous réserve d'un consentement explicite, les données d'interaction (clics, temps de latence) sont utilisées de manière <strong>anonymisée</strong> pour améliorer la plateforme.</li>
                </ul>

                <h3 class="text-sv-blue font-bold text-lg mt-6">2. Durée de conservation</h3>
                <p>
                    Les données sont conservées pendant toute la durée d'activation du compte professionnel. Celles des bénéficiaires sont conservées jusqu'à la demande de suppression par le travailleur social ou l'utilisateur.
                </p>

                <h3 class="text-sv-blue font-bold text-lg mt-6">3. Vos droits (Accès, Modification, Suppression)</h3>
                <p>
                    Vous disposez d'un droit d'accès, de rectification et d'effacement de vos données. Pour les exercer, utilisez les fonctionnalités de l'application ou contactez-nous :<br>
                    <a href="mailto:dpo@savoirsvivants.fr" class="inline-flex items-center gap-2 mt-2 bg-sv-blue/10 text-sv-blue font-bold px-4 py-2 rounded-lg hover:bg-sv-blue hover:text-white transition-colors no-underline">
                        [EMAIL DPO À COMPLÉTER]
                    </a>
                </p>

                <h3 class="text-sv-blue font-bold text-lg mt-6">4. Utilisation des Cookies</h3>
                <p>
                    Usuel utilise <strong>uniquement des cookies dits "strictement nécessaires"</strong> (cookies de session, protection CSRF). Aucun cookie de ciblage publicitaire n'est utilisé. Aucun bandeau de consentement n'est donc requis.
                </p>
            </div>
        </div>

    </div>
</section>
@endsection
