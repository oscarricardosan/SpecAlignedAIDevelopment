<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAID — Login</title>
    <link rel="icon" type="image/png" href="/assets/favicon.png">
    <script src="https://cdn.tailwindcss.com?v=2"></script>
    <link rel="stylesheet" href="/css/said.css?v=3">
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
                <div class="bg-coral/10 border border-coral/30 text-coral rounded-lg p-3 mb-4 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="/login" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Email</label>
                    <input name="email" type="email" value="{{ old("email") }}"
                           class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition" required autofocus>
                </div>
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Password</label>
                    <input name="password" type="password"
                           class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition" required>
                </div>
                <div class="flex items-center gap-2">
                    <input name="remember" type="checkbox" id="remember" class="rounded border-cool text-teal focus:ring-teal">
                    <label for="remember" class="text-sm text-warm">Remember me</label>
                </div>
                <button type="submit"
                        class="w-full bg-teal text-white rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-teal-dark transition shadow-sm">
                    Sign in
                </button>
            </form>
        </div>

    </div>

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
</body>
</html>
