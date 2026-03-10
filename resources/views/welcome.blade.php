@extends('layouts.app')

@section('title', 'Usuel - Évaluation de la Littératie Numérique')

@section('content')

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Courier+Prime:wght@700&display=swap"
        rel="stylesheet">

    <nav class="bg-white border-b-4 border-sv-green sticky top-0 z-50 shadow-sm">
        <div class="max-w-4xl mx-auto px-6 py-4 flex items-center justify-between">
            <span class="font-mono text-2xl font-bold text-sv-blue">Usuel</span>
            <a href="{{ route('login') }}"
                class="bg-sv-green text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:opacity-90 transition-opacity">
                Se connecter
            </a>
        </div>
    </nav>

    <section class="bg-sv-blue text-white px-6 pt-20 pb-28 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 rounded-full opacity-5 bg-white -translate-y-1/2 translate-x-1/3"></div>
        <div class="max-w-4xl mx-auto relative">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6 max-w-2xl">
                Évaluez les compétences numériques de vos bénéficiaires
            </h1>
            <p class="text-lg md:text-xl leading-relaxed opacity-85 max-w-xl mb-10 font-medium">
                Usuel accompagne les travailleurs sociaux et formateurs pour diagnostiquer précisément le niveau de
                littératie numérique et construire des parcours adaptés.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('login') }}"
                    class="bg-sv-green text-white font-bold text-base px-8 py-4 rounded-xl hover:opacity-90 transition-opacity">
                    Accéder à la plateforme
                </a>
                <a href="#en-savoir-plus"
                    class="border-2 border-white text-white font-bold text-base px-8 py-4 rounded-xl hover:bg-white hover:text-sv-blue transition-colors">
                    Découvrir l'outil
                </a>
            </div>
        </div>
    </section>

    <section class="bg-gray-50 border-b border-gray-200 px-6 py-12">
        <div class="max-w-4xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8">

            <div class="flex items-start gap-4">
                <div
                    class="w-12 h-12 min-w-[3rem] bg-white border-2 border-sv-green rounded-2xl flex items-center justify-center text-xl">
                    ⚡</div>
                <div>
                    <p class="font-bold text-sv-blue text-sm">Rapide</p>
                    <p class="text-gray-500 text-sm leading-snug mt-0.5">Évaluation en 15–20 minutes</p>
                </div>
            </div>

            <div class="flex items-start gap-4">
                <div
                    class="w-12 h-12 min-w-[3rem] bg-white border-2 border-sv-green rounded-2xl flex items-center justify-center text-xl">
                    🔒</div>
                <div>
                    <p class="font-bold text-sv-blue text-sm">Confidentiel</p>
                    <p class="text-gray-500 text-sm leading-snug mt-0.5">Données anonymisées, conformes RGPD</p>
                </div>
            </div>

            <div class="flex items-start gap-4">
                <div
                    class="w-12 h-12 min-w-[3rem] bg-white border-2 border-sv-green rounded-2xl flex items-center justify-center text-xl">
                    📊</div>
                <div>
                    <p class="font-bold text-sv-blue text-sm">Actionnable</p>
                    <p class="text-gray-500 text-sm leading-snug mt-0.5">Résultats clairs et orientants</p>
                </div>
            </div>

            <div class="flex items-start gap-4">
                <div
                    class="w-12 h-12 min-w-[3rem] bg-white border-2 border-sv-green rounded-2xl flex items-center justify-center text-xl">
                    🆓</div>
                <div>
                    <p class="font-bold text-sv-blue text-sm">Gratuit</p>
                    <p class="text-gray-500 text-sm leading-snug mt-0.5">Aucun coût</p>
                </div>
            </div>

        </div>
    </section>

    <section id="en-savoir-plus" class="bg-white px-6 py-20 border-b border-gray-200">
        <div class="max-w-4xl mx-auto grid md:grid-cols-2 gap-14 items-center">

            <div>
                <p class="text-sv-green font-bold text-xs tracking-widest uppercase mb-3">Le projet</p>
                <h2 class="text-3xl md:text-4xl font-extrabold text-sv-blue leading-tight mb-5">
                    Un outil pensé pour les professionnels de terrain
                </h2>
                <p class="text-gray-600 text-base leading-relaxed mb-4">
                    Usuel permet aux travailleurs sociaux et formateurs d'identifier précisément les freins numériques de
                    chaque bénéficiaire — sans que ce dernier ait besoin de compétences techniques pour y répondre.
                </p>
                <p class="text-gray-600 text-base leading-relaxed">
                    En transformant des indicateurs techniques en orientations concrètes, Usuel facilite la construction de
                    parcours de formation réellement adaptés à chaque situation.
                </p>
            </div>

            <div class="bg-sv-blue rounded-2xl p-8 text-white">
                <p class="text-xs font-bold tracking-widest uppercase opacity-60 mb-5">Compétences évaluées</p>
                <ul class="space-y-0">
                    @foreach (['Utiliser un ordinateur ou un smartphone', 'Naviguer sur Internet en sécurité', 'Envoyer des e-mails et messages', 'Faire des démarches administratives en ligne', 'Reconnaître les arnaques et fausses informations'] as $item)
                        <li class="flex items-center gap-3 py-3 border-b border-white/10 last:border-0">
                            <span class="w-2.5 h-2.5 min-w-[0.625rem] rounded-full bg-sv-green"></span>
                            <span class="text-sm font-medium opacity-90">{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </section>

    <section id="comment-ca-marche" class="bg-gray-50 px-6 py-20 border-b border-gray-200">
        <div class="max-w-4xl mx-auto">
            <p class="text-sv-green font-bold text-xs tracking-widest uppercase mb-3">Étape par étape</p>
            <h2 class="text-3xl md:text-4xl font-extrabold text-sv-blue mb-12">Comment ça marche ?</h2>

            <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-6">

                @foreach ([['01', '🧑‍💼', 'Créez un accès', 'Ouvrez une session sécurisée pour votre bénéficiaire en quelques clics.'], ['02', '💬', 'Le bénéficiaire répond', 'Des questions simples sur son quotidien numérique. Pas de jargon technique.'], ['03', '📋', 'Analysez les résultats', 'Vous recevez un rapport clair avec le niveau identifié et les axes de progression.'], ['04', '🚀', 'Orientez vers la formation', 'Proposez le parcours le plus adapté grâce aux recommandations générées.']] as [$num, $icon, $title, $desc])
                    <div
                        class="bg-white border-2 border-gray-200 rounded-2xl p-6 hover:border-sv-green hover:-translate-y-1 transition-all duration-200">
                        <p class="font-extrabold text-4xl text-sv-green opacity-20 leading-none mb-3">{{ $num }}
                        </p>
                        <p class="text-3xl mb-3">{{ $icon }}</p>
                        <h3 class="font-bold text-sv-blue text-base mb-2">{{ $title }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ $desc }}</p>
                    </div>
                @endforeach

            </div>
        </div>
    </section>

    <section class="bg-sv-blue text-white px-6 py-16 border-b border-white/10">
        <div class="max-w-4xl mx-auto flex flex-wrap items-center gap-10">
            <p class="text-6xl">🛡️</p>
            <div class="flex-1 min-w-[260px]">
                <h2 class="text-2xl md:text-3xl font-extrabold mb-3">Les données de vos bénéficiaires sont protégées</h2>
                <p class="text-base leading-relaxed opacity-85">
                    Usuel garantit l'anonymat complet de toutes les données sensibles. Aucune information personnelle n'est
                    partagée sans consentement explicite. La plateforme est hébergée en France et respecte le RGPD.
                </p>
            </div>
        </div>
    </section>

    <section class="bg-gray-50 px-6 py-16 border-b border-gray-200">
        <div class="max-w-4xl mx-auto">
            <p class="text-sv-green font-bold text-xs tracking-widest uppercase mb-3">Ils nous font confiance</p>
            <h2 class="text-3xl md:text-4xl font-extrabold text-sv-blue mb-8">Nos partenaires</h2>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-5">
                <img src="{{ asset('storage/logo_icube.png') }}" alt="Laboratoire ICube" class="h-12 w-auto object-contain">
            </div>
        </div>
        </div>
    </section>

    <section class="bg-white px-6 py-20 border-b border-gray-200 text-center">
        <div class="max-w-2xl mx-auto">
            <p class="text-sv-green font-bold text-xs tracking-widest uppercase mb-3">Prêt à commencer ?</p>
            <h2 class="text-3xl md:text-4xl font-extrabold text-sv-blue mb-5">
                Accompagnez mieux vos bénéficiaires dès aujourd'hui
            </h2>
            <p class="text-gray-500 text-base leading-relaxed mb-8">
                Gratuit, rapide et sécurisé.
            </p>
            <a href="{{ route('login') }}"
                class="inline-block bg-sv-green text-white font-bold text-lg px-10 py-5 rounded-xl hover:opacity-90 transition-opacity">
                Accéder à la plateforme →
            </a>
        </div>
    </section>

    <section class="bg-gray-50 px-6 py-20">
        <div class="max-w-4xl mx-auto">
            <p class="text-sv-green font-bold text-xs tracking-widest uppercase mb-3">Rejoindre le réseau</p>
            <h2 class="text-3xl md:text-4xl font-extrabold text-sv-blue mb-5">Devenir partenaire</h2>
            <p class="text-gray-600 text-base leading-relaxed mb-3 max-w-2xl">
                Savoirs Vivants est ouvert à toutes les formes de coopération : soutien financier, mécénat matériel,
                co-construction de projets, mise à disposition d'espaces ou de compétences, participation à des évènements…
            </p>
            <p class="text-gray-600 text-base leading-relaxed mb-3 max-w-2xl">
                Nous cherchons à bâtir des partenariats durables et utiles pour les habitants et pour les acteurs du
                territoire.
            </p>
            <p class="text-gray-600 text-base">
                Nous contacter :
                <a href="mailto:contact@savoirsvivants.fr" class="text-sv-green font-semibold hover:underline">
                    contact@savoirsvivants.fr
                </a>
            </p>
        </div>
    </section>

    <footer class="bg-sv-blue text-white/70 text-center py-7 text-sm">
        <p>© Association Savoirs Vivants — Projet de recherche en littératie numérique.</p>
    </footer>

@endsection
