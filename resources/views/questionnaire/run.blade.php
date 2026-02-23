@extends('layouts.app')

@section('title', 'Passation du questionnaire')

@section('content')
    <section class="min-h-screen bg-gray-50 flex flex-col pt-12">
        <livewire:questionnaire-run />
    </section>
@endsection
