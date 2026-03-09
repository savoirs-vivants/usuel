@extends('layouts.app')

@section('title', 'Usuel - Statistiques')

@section('content')
    <section class="flex min-h-screen bg-gray-50">
        <div class="ml-64 flex-1 p-8">
            @livewire('statistiques-public')
        </div>
    </section>
@endsection
