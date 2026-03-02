@extends('layouts.app')

@section('title', 'Usuel - Modifier l\'utilisateur')

@section('content')
    <section class="flex min-h-screen bg-gray-100">
        <div class="ml-60 flex-1 p-10">
            @livewire('edit-user', ['user' => $user])
        </div>
    </section>
@endsection
