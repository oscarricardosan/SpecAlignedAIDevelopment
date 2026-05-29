@extends('layouts.app')

@section('title', $application->name)
@section('heading', $application->name)
@section('subheading', $project->name)

@section('actions')
    <a href="{{ route('apps.edit', [$project, $application]) }}"
       class="btn btn-outline btn-sm gap-2 transition duration-200">
        <iconify-icon icon="heroicons:pencil" style="font-size: 16px"></iconify-icon>
        Edit
    </a>
    <a href="{{ route('apps.index', $project) }}"
       class="btn btn-ghost btn-sm gap-2 transition duration-200">
        <iconify-icon icon="heroicons:arrow-left" style="font-size: 16px"></iconify-icon>
        All applications
    </a>
@endsection

@section('content')

<div class="max-w-5xl mx-auto space-y-4" x-data="{ tab: 'identity' }">

    @include('partials.apps.show.hero')

    {{-- Tab bar --}}
    <div class="card bg-base-100 border border-cool/50 shadow-sm">
        <div class="card-body p-3">
            <div class="flex items-center gap-1 flex-wrap">
                @php
                $tabs = [
                    'identity'     => ['Identity', 'heroicons:identification'],
                    'architecture' => ['Architecture', 'heroicons:square-3-stack-3d'],
                    'storage'      => ['Storage', 'heroicons:circle-stack'],
                ];
                @endphp
                @foreach ($tabs as $key => [$label, $icon])
                <button type="button" @click="tab = '{{ $key }}'"
                        class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-semibold transition duration-200 border"
                        :class="tab === '{{ $key }}' ? 'bg-teal/10 text-teal border-teal/30' : 'text-cool border-transparent hover:text-navy hover:bg-cool/10'">
                    <iconify-icon icon="{{ $icon }}" style="font-size: 16px"></iconify-icon>
                    <span class="hidden sm:inline">{{ $label }}</span>
                </button>
                @endforeach
            </div>
        </div>
    </div>

    @include('partials.apps.show.tab-identity')
    @include('partials.apps.show.tab-architecture')
    @include('partials.apps.show.tab-storage')

</div>

@endsection
