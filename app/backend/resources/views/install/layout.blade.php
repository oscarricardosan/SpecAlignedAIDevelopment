<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAID — Installation</title>
    <link rel="icon" type="image/png" href="/assets/favicon.png">
    <script src="https://cdn.tailwindcss.com?v=2"></script>
    <link rel="stylesheet" href="/css/said.css?v=3">
    <script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("button[type=submit]").forEach(btn => {
        btn.form.addEventListener("submit", function () {
            btn.disabled = true;
            btn.innerHTML = `<span class="flex items-center justify-center gap-2"><svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg> Processing...</span>`;
        });
    });
});
    </script>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl flex flex-col md:flex-row overflow-hidden">

        {{-- Left panel: Branding + step info --}}
        <div class="text-white p-8 md:w-1/2 flex flex-col items-center justify-center text-center relative overflow-hidden" style="background: linear-gradient(to bottom right, #133d5b, #000508)">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-10 left-10 w-32 h-32 rounded-full bg-teal-light blur-3xl"></div>
                <div class="absolute bottom-10 right-10 w-40 h-40 rounded-full bg-teal blur-3xl"></div>
            </div>

            <div class="relative z-10">
                <img src="/assets/pet.png" alt="SAID" class="w-32 h-auto mx-auto mb-4 drop-shadow-lg">
                <h1 class="text-2xl font-bold mb-1 tracking-tight">SAID</h1>
                <p class="text-teal-light/70 text-sm mb-6">Spec-Aligned AI Development</p>

                {{-- Step indicator --}}
                <div class="flex items-center justify-center gap-3 mb-8">
                    @php
                        $currentStep = request()->is('install/user') ? 2 : (request()->is('install/complete') ? 3 : 1);
                        $steps = [
                            1 => ['label' => 'Database', 'desc' => 'Connect to PostgreSQL'],
                            2 => ['label' => 'Admin user', 'desc' => 'Create your account'],
                            3 => ['label' => 'Ready', 'desc' => 'Start using SAID'],
                        ];
                    @endphp
                    @foreach ($steps as $i => $step)
                        @php
                            $done = $i < $currentStep;
                            $active = $i === $currentStep;
                            $upcoming = $i > $currentStep;
                        @endphp
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold transition
                                {{ $done    ? 'bg-teal-light text-navy' : '' }}
                                {{ $active  ? 'bg-white text-navy ring-4 ring-teal/40' : '' }}
                                {{ $upcoming ? 'border border-white/20 text-white/40' : '' }}">
                                {{ $done ? '✓' : $i }}
                            </div>
                            <span class="text-xs {{ $active ? 'text-white font-semibold' : 'text-white/50' }}">
                                {{ $step['label'] }}
                            </span>
                        </div>
                        @if ($i < 3)
                            <div class="w-6 h-0.5 {{ $i < $currentStep ? 'bg-teal-light' : 'bg-white/10' }} -mt-4"></div>
                        @endif
                    @endforeach
                </div>

                <div class="border border-white/15 rounded-lg p-4">
                    <p class="text-sm font-semibold text-white mb-1">Step {{ $currentStep }}: {{ $steps[$currentStep]['label'] }}</p>
                    <p class="text-xs text-teal-light/70 leading-relaxed">@yield('step_description')</p>
                </div>
            </div>
        </div>

        {{-- Right panel: Form --}}
        <div class="p-8 md:w-1/2 flex flex-col justify-center">
            @if ($errors->any())
                <div class="bg-coral/10 border border-coral/30 text-coral rounded-lg p-3 mb-6 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            @yield('content')
        </div>

    </div>
</body>
</html>
