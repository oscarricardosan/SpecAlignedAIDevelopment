<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAID — Installation</title>
    <link rel="icon" type="image/png" href="/assets/favicon.png">
    <script src="https://cdn.tailwindcss.com?v=2"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@2/dist/iconify-icon.min.js"></script>
    <link rel="stylesheet" href="/css/said.css?v=9">
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
                        $currentStep = request()->is('install/user') ? 3 : (request()->is('install/complete') ? 4 : (request()->is('install/restart') || request()->is('install/storage') ? 2 : 1));
                        $steps = [
                            1 => ['label' => 'Database', 'desc' => 'Connect to PostgreSQL'],
                            2 => ['label' => 'Storage', 'desc' => 'S3 and file root'],
                            3 => ['label' => 'Admin user', 'desc' => 'Create your account'],
                            4 => ['label' => 'Ready', 'desc' => 'Start using SAID'],
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
                        @if ($i < 4)
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
                <div role="alert" class="alert alert-error mb-6 text-sm">
                    <iconify-icon icon="heroicons:exclamation-triangle" class="w-5 h-5"></iconify-icon>
                    <span>
                        @foreach ($errors->all() as $error)
                            {{ $error }}@if (!$loop->last)<br>@endif
                        @endforeach
                    </span>
                </div>
            @endif
            @yield('content')
        </div>

    </div>
</body>
</html>
