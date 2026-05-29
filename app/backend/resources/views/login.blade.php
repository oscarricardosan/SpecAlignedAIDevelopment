<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAID — Login</title>
    <link rel="icon" type="image/png" href="/assets/favicon.png">
    <script src="https://cdn.tailwindcss.com?v=2"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@2/dist/iconify-icon.min.js"></script>
    <link rel="stylesheet" href="/css/said.css?v=9">
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl flex flex-col md:flex-row overflow-hidden">

        {{-- Left: branding --}}
        <div class="text-white p-8 md:w-1/2 flex flex-col items-center justify-center text-center relative overflow-hidden" style="background: linear-gradient(to bottom right, #133d5b, #000508)">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-10 left-10 w-32 h-32 rounded-full bg-teal-light blur-3xl"></div>
                <div class="absolute bottom-10 right-10 w-40 h-40 rounded-full bg-teal blur-3xl"></div>
            </div>
            <img src="/assets/pet.png" alt="SAID" class="w-40 h-auto mb-4 drop-shadow-lg relative z-10">
            <div class="relative z-10">
                <p class="text-3xl font-bold mb-2 tracking-tight">Welcome back</p>
                <p class="text-base font-light leading-relaxed max-w-xs mx-auto text-teal-light/80">
                    Your workspace for cooperation<br>
                    and co-construction with <span class="font-semibold text-teal-light">AI</span>
                </p>
            </div>
        </div>

        {{-- Right: Login form --}}
        <div class="p-8 md:w-1/2 flex flex-col justify-center">
            <h2 class="text-2xl font-bold text-navy mb-6">Sign in</h2>

            @if ($errors->any())
                <div role="alert" class="alert alert-error mb-4 text-sm">
                    <iconify-icon icon="heroicons:exclamation-triangle" style="font-size: 20px"></iconify-icon>
                    <span>
                        @foreach ($errors->all() as $error)
                            {{ $error }}@if (!$loop->last)<br>@endif
                        @endforeach
                    </span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4"
                  x-data="{ submitting: false }" @submit="submitting = true">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Email</label>
                    <input name="email" type="email" value="{{ old('email') }}"
                           class="input input-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition" required autofocus>
                </div>
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Password</label>
                    <input name="password" type="password"
                           class="input input-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition" required>
                </div>
                <div class="flex items-center gap-2">
                    <input name="remember" type="checkbox" id="remember" class="checkbox checkbox-sm border-cool text-teal focus:ring-teal">
                    <label for="remember" class="text-sm text-warm">Remember me</label>
                </div>
                <button type="submit"
                        :disabled="submitting"
                        class="btn btn-primary w-full disabled:opacity-60 disabled:cursor-not-allowed">
                    <span x-show="!submitting">Sign in</span>
                    <span x-show="submitting" class="flex items-center gap-2">
                        <iconify-icon icon="svg-spinners:180-ring" style="font-size: 16px"></iconify-icon>
                        Processing&hellip;
                    </span>
                </button>
            </form>
        </div>

    </div>
</body>
</html>
