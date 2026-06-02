@extends('layouts.app')

@section('title', 'Usuel - Politique de Confidentialité')

@section('content')
<section class="bg-gray-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8 font-grotesk">
    <div class="max-w-4xl mx-auto relative z-10">

        <div class="mb-10 text-center relative">
            <a href="javascript:history.back()" class="absolute left-0 top-1/2 -translate-y-1/2 text-gray-400 hover:text-sv-blue transition-colors p-2 bg-white rounded-full shadow-sm hover:shadow-md border border-gray-100 hidden md:flex" title="Retour">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h1 class="font-mono font-bold text-3xl sm:text-4xl text-sv-blue tracking-tight">Politique de Confidentialité</h1>
            <p class="text-gray-500 mt-3 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
                Protection de vos données personnelles (RGPD) et gestion de la vie privée sur l'application Usuel.
            </p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden mb-10">

            <div class="bg-sv-green/10 px-8 py-5 border-b border-sv-green/20 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-sv-green flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h2 class="font-mono font-bold text-xl text-sv-blue">Traitement des Données</h2>
            </div>

            <div class="p-8 md:p-10 prose prose-sm sm:prose-base max-w-none text-gray-600">
                <p class="lead font-medium text-gray-800 mt-0">
                    L'association <strong>Savoirs Vivants</strong> s'engage à ce que les traitements de données effectués sur l'application d'évaluation <strong>Usuel</strong> soient conformes au Règlement Général sur la Protection des Données (RGPD).
                </p>

                <h3 class="text-sv-blue font-bold text-lg mt-8">1. Données collectées et Finalités</h3>
                <p>Les données collectées servent exclusivement au fonctionnement de l'application et se divisent en trois catégories :</p>
                <ul class="space-y-2 mt-2 pl-5 list-disc">
                    <li>
                        <strong>Gestion des professionnels :</strong> Identité, adresse e-mail, structure de rattachement et mots de passe hachés. Ces données servent à gérer l'accès sécurisé à la plateforme.
                    </li>
                    <li>
                        <strong>Évaluation des bénéficiaires :</strong> Nom, prénom, données socio-démographiques (âge, diplôme, CSP), ainsi que les scores de littératie numérique. Ces données permettent d'assurer le suivi pédagogique par le travailleur social.
                    </li>
                    <li>
                        <strong>Projet de recherche :</strong> <em>Sous réserve d'un consentement explicite avant le test</em>, l'application collecte des données d'interaction (nombre de clics, temps de latence, suivi de la souris) à des fins de recherche scientifique et d'amélioration des interfaces. Ces données sont anonymisées lors de leur analyse.
                    </li>
                </ul>

                <hr class="border-gray-100 my-6">

                <h3 class="text-sv-blue font-bold text-lg">2. Base légale</h3>
                <p>Les traitements sont effectués sur la base :</p>
                <ul class="space-y-1 mt-2 pl-5 list-disc">
                    <li>De <strong>votre consentement</strong> (pour la collecte des données de tracking de recherche).</li>
                    <li>De <strong>l’intérêt légitime</strong> de l’association à fournir un outil d'évaluation fonctionnel et à améliorer la navigation.</li>
                    <li>De <strong>l'exécution d'un contrat</strong> ou de conditions d'utilisation pour la gestion des comptes professionnels.</li>
                </ul>

                <hr class="border-gray-100 my-6">

                <h3 class="text-sv-blue font-bold text-lg">3. Durée de conservation</h3>
                <ul class="space-y-2 mt-2 pl-5 list-disc">
                    <li><strong>Comptes professionnels :</strong> Conservés pendant toute la durée d'activation du compte.</li>
                    <li><strong>Données des bénéficiaires :</strong> Conservées jusqu'à la demande de suppression par le professionnel accompagnant ou l'utilisateur concerné.</li>
                    <li><strong>Données de recherche et statistiques :</strong> Conservées pour une durée maximale de <strong>25 mois</strong>, conformément aux recommandations en vigueur.</li>
                </ul>

                <hr class="border-gray-100 my-6">

                <h3 class="text-sv-blue font-bold text-lg">4. Hébergement et Sécurité</h3>
                <p>
                    L'application est hébergée par <strong>Infomaniak</strong>, situé en <strong>Suisse</strong>. Les données sont stockées sur des serveurs reconnus comme offrant un niveau de protection adéquat (équivalent RGPD).<br>
                    Nous mettons tout en œuvre pour protéger vos données contre tout accès non autorisé, modification, divulgation ou destruction. L'ensemble des échanges d'informations sur l'application Usuel est sécurisé par le protocole <strong>HTTPS</strong>.
                </p>

                <hr class="border-gray-100 my-6">

                <h3 class="text-sv-blue font-bold text-lg">5. Cookies et mesure d'audience</h3>
                <p>
                    L'application Usuel utilise <strong>uniquement des cookies strictement nécessaires</strong> à son bon fonctionnement (maintien de la session de connexion, sécurité CSRF). L'application n'utilise aucun cookie de ciblage publicitaire.<br>
                    Si des cookies de mesure d'audience non essentiels venaient à être utilisés, un bandeau vous permettrait de les accepter ou de les refuser lors de votre première visite.
                </p>

                <hr class="border-gray-100 my-6">

                <h3 class="text-sv-blue font-bold text-lg">6. Vos droits</h3>
                <p>
                    Conformément au RGPD, vous (et les bénéficiaires que vous accompagnez) disposez des droits suivants :
                </p>
                <ul class="space-y-1 mt-2 pl-5 list-disc">
                    <li>Droit d’accès, de rectification et de suppression de vos données,</li>
                    <li>Droit d’opposition au traitement,</li>
                    <li>Droit à la limitation et à la portabilité des données.</li>
                </ul>
                <p class="mt-4">
                    Pour exercer ces droits, vous pouvez utiliser les fonctionnalités de suppression directement dans l'application ou nous écrire à :<br>
                    <a href="mailto:contact@savoirsvivants.fr" class="inline-flex items-center gap-2 mt-3 bg-sv-blue/10 text-sv-blue font-bold px-4 py-2.5 rounded-xl hover:bg-sv-blue hover:text-white transition-colors no-underline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        contact@savoirsvivants.fr
                    </a>
                </p>
                <p class="text-sm mt-3">Nous répondrons à votre demande dans un délai d’un mois maximum.</p>

                <hr class="border-gray-100 my-6">

                <div class="text-sm text-gray-400 font-medium">
                    <p>La présente politique peut être mise à jour en cas d’évolution légale ou technique.</p>
                    <p class="mt-1">Dernière mise à jour : 11 mars 2026.</p>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
