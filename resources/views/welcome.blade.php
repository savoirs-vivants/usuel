@extends('layouts.app')

@section('title', 'Savoirs Vivants - Évaluation de la Littératie Numérique')

@section('content')

<nav class="bg-white border-b-4 border-sv-green">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <span class="self-center text-2xl font-bold font-mono text-sv-blue">Usuel</span>
    </div>
</nav>

<section class="bg-sv-blue text-white px-6 pt-20 pb-16">
    <div class="max-w-3xl mx-auto">
        <h1 class="font-mono font-bold text-3xl md:text-4xl lg:text-5xl leading-snug mb-5">
            Évaluation du niveau de littératie numérique
        </h1>
        <p class="font-mono italic text-sm md:text-base leading-relaxed opacity-90 max-w-xl mb-10">
            Un outil complet conçu pour évaluer et accompagner le développement des compétences numériques des bénéficiaires.
        </p>
        <div class="flex flex-wrap gap-4">
            <a href="#en-savoir-plus"
                class="border-2 border-white text-white rounded-lg px-6 py-3 font-bold text-sm
                          hover:bg-white hover:text-sv-blue transition-colors duration-200">
                Découvrir le projet
            </a>
            <a href="/login"
                class="bg-sv-green border-2 border-sv-green text-white rounded-lg px-6 py-3 font-bold text-sm
                          hover:opacity-90 transition-opacity duration-200">
                Se connecter à la plateforme
            </a>
        </div>
    </div>
</section>

<section class="bg-gray-50 px-6 py-16 border-b border-gray-200">
    <div class="max-w-3xl mx-auto">
        <h2 class="font-mono font-bold text-3xl text-sv-blue mb-8">
            Nos partenaires
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
            <div class="bg-gray-300 rounded-md h-20"></div>
            <div class="bg-gray-300 rounded-md h-20"></div>
            <div class="bg-gray-300 rounded-md h-20"></div>
            <div class="bg-gray-300 rounded-md h-20"></div>
        </div>
    </div>
</section>

<section id="en-savoir-plus" class="bg-white px-6 py-16 border-b border-gray-200">
    <div class="max-w-3xl mx-auto">
        <h2 class="font-mono font-bold text-3xl text-sv-blue mb-6">
            Usuel : Le tremplin vers l'autonomie numérique
        </h2>
        <p class="text-gray-600 leading-relaxed text-m mb-4">
            Usuel est une plateforme sécurisée conçue pour diagnostiquer précisément le niveau de littératie numérique des citoyens.
        </p>
        <p class="text-gray-600 leading-relaxed text-m">
            L'outil permet aux travailleurs sociaux et formateurs d'identifier les freins invisibles de chaque bénéficiaire. En transformant des indicateurs techniques en parcours d'accompagnement concrets, USUEL facilite une orientation personnalisée vers les modules de formation les plus adaptés, tout en garantissant un anonymat total des données sensibles.
        </p>
    </div>
</section>

<section class="bg-gray-50 px-6 py-16">
    <div class="max-w-3xl mx-auto">
        <h2 class="font-mono font-bold text-3xl text-sv-blue mb-6">
            Comment devenir partenaire ?
        </h2>
        <p class="text-gray-600 leading-relaxed text-m mb-2">
            Savoirs Vivants est ouvert à toutes les formes de coopération : soutien financier, mécénat matériel, co-construction de projets, mise à disposition d'espaces ou compétences, participation à des évènements…
        </p>
        <p class="text-gray-600 leading-relaxed text-m mb-2">
            Nous cherchons à bâtir des partenariats durables et utiles pour les habitants et pour les acteurs du territoire.
        </p>
        <p class="text-gray-600 leading-relaxed text-m">
            Pour nous contacter :
            <a href="mailto:contact@savoirsvivants.fr" class="text-sv-green font-semibold hover:underline">
                contact@savoirsvivants.fr
            </a>
        </p>
    </div>
</section>

<footer class="bg-sv-blue text-white/80 text-center py-8 text-sm">
    <p>© Association Savoirs Vivants. Projet de recherche en littératie numérique.</p>
</footer>

@endsection
