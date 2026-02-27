@extends('layouts.app')

@section('title', 'Usuel - Statistiques')

@section('content')
    <section class="flex min-h-screen bg-gray-100 p-8">
        <div class="ml-60 flex-1">
            @livewire('statistiques-public')
        </div>
    </section>
@endsection
