@extends('layouts.app')

@section('title', 'Edit ' . $application->name)
@section('heading', 'Edit Application')
@section('subheading', $project->name . ' — ' . $application->name)

@section('content')

<div class="flex flex-nowrap gap-6 items-start" x-data="appForm(@js($projectPath), @js($application))">

<div class="flex-1 min-w-0 space-y-6">

<form id="app-form" action="{{ route('apps.update', [$project, $application]) }}" method="POST"
      @submit="submitting = true">
    @csrf
    @method('PUT')

    {{-- Step indicator --}}
    <div class="card bg-base-100 border border-cool/50 shadow-sm  mb-6">
        <div class="card-body p-4">
            <div class="flex items-center justify-center gap-1">
                @foreach ([1 => 'Platform', 2 => 'Language', 3 => 'Framework', 4 => 'Storage', 5 => 'Arch.', 6 => 'Quality', 7 => 'Identity'] as $i => $label)
                    <button type="button" @click="goToStep({{ $i }})" class="flex items-center gap-1.5 group" :disabled="step < {{ $i }} && !isStepComplete({{ $i - 1 }})">
                        <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold border transition shrink-0"
                              :class="step === {{ $i }}
                                  ? 'bg-teal text-white border-teal'
                                  : (step > {{ $i }}
                                      ? 'bg-teal/10 text-teal border-teal/30 cursor-pointer'
                                      : 'text-cool/40 border-cool/30')">
                            <span x-show="step > {{ $i }}">✓</span>
                            <span x-show="step <= {{ $i }}" x-text="{{ $i }}"></span>
                        </span>
                        <span class="text-xs font-semibold hidden sm:inline transition"
                              :class="step >= {{ $i }} ? 'text-navy' : 'text-cool/40'">{{ $label }}</span>
                    </button>
                    @if ($i < 7)
                        <span class="w-4 h-px shrink-0 transition" :class="step > {{ $i }} ? 'bg-teal/40' : 'bg-cool/20'"></span>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Step content --}}
    <div class="card bg-base-100 border border-cool/50 shadow-sm">
        <div class="card-body p-6">
            @include('partials.apps.step1-platform')
            @include('partials.apps.step2-language')
            @include('partials.apps.step3-framework')
            @include('partials.apps.step4-storage')
            @include('partials.apps.step5-architecture')
            @include('partials.apps.step6-quality')
            @include('partials.apps.step7-identity')
        </div>
    </div>

</form>

{{-- Navigation --}}
<div class="card bg-base-100">
    <div class="flex items-center gap-3">
        <button type="button" @click="goToStep(step - 1)"
                x-show="step > 1"
                class="btn btn-ghost btn-sm text-sm text-warm hover:text-navy transition duration-200">
            <iconify-icon icon="heroicons:arrow-left" style="font-size: 16px"></iconify-icon>
            Back
        </button>
        <button type="button" @click="goToStep(step + 1)"
                x-show="step < 7"
                class="btn btn-primary btn-sm ml-auto transition duration-200"
                :disabled="!canProceed()">
            Next
            <iconify-icon icon="heroicons:arrow-right" style="font-size: 16px"></iconify-icon>
        </button>
        <button type="submit"
                form="app-form"
                x-show="step === 7"
                :disabled="submitting"
                class="btn btn-primary btn-sm ml-auto disabled:opacity-60 disabled:cursor-not-allowed transition duration-200">
            <span x-show="!submitting">Save changes</span>
            <span x-show="submitting" class="flex items-center gap-2">
                <iconify-icon icon="svg-spinners:180-ring" style="font-size: 16px"></iconify-icon>
                Saving…
            </span>
        </button>
        <a href="{{ route('apps.show', [$project, $application]) }}" class="btn btn-ghost btn-sm text-sm text-warm hover:text-navy transition duration-200">Cancel</a>
    </div>
</div>

</div>

@include('partials.apps.summary')

</div>

@endsection

@push('scripts')
    @include('partials.apps.scripts')
@endpush
