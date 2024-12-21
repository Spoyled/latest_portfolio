@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Applicants for: {{ $post->title }}</h1>

    @foreach ($post->applicants as $applicant)
        <div class="bg-white shadow rounded p-4 mb-4">
            <p><strong>Name:</strong> {{ $applicant->name }}</p>
            <p><strong>Email:</strong> {{ $applicant->email }}</p>
            <p>
                <strong>CV:</strong> 
                <a href="{{ asset('storage/cvs/' . $applicant->pivot->cv_path) }}" target="_blank" class="text-blue-500 underline">Download CV</a>
            </p>
        </div>
    @endforeach
</div>
@endsection
