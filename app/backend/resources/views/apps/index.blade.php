@extends('layouts.app')

@section('title', 'Applications')
@section('heading', 'Applications')

@section('content')

<div class="max-w-2xl mx-auto text-center py-16">
    <div class="w-16 h-16 rounded-full bg-teal/15 flex items-center justify-center mx-auto mb-5">
        <iconify-icon icon="heroicons:computer-desktop" class="w-8 h-8 text-teal"></iconify-icon>
    </div>
    <h2 class="text-lg font-bold text-navy mb-2">No applications yet</h2>
    <p class="text-sm text-warm max-w-md mx-auto mb-6 leading-relaxed">
        Applications represent a deliverable — a web app, mobile app, or API. Create your first project, then add applications to it.
    </p>
    <a href="{{ route('apps.create') }}"
       class="btn btn-primary gap-2">
        <iconify-icon icon="heroicons:plus" class="w-4 h-4"></iconify-icon>
        Create your first application
    </a>
</div>

@endsection
