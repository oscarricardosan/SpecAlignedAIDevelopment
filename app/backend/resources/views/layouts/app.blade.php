<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAID — @yield('title', 'Dashboard')</title>
    <link rel="icon" type="image/png" href="/assets/favicon.png">
    <script src="https://cdn.tailwindcss.com?v=2"></script>
    <link rel="stylesheet" href="/css/said.css?v=7">
</head>
<body class="min-h-screen flex text-navy">

    {{-- Sidebar --}}
    <aside class="w-60 flex flex-col shrink-0 min-h-screen text-white" style="background: linear-gradient(to bottom, #133d5b, #000508)">
        {{-- Brand --}}
        <div class="px-5 pt-5 pb-4">
            <div class="flex items-center gap-2.5">
                <img src="/assets/favicon.png" alt="SAID" class="w-8 h-8">
                <span class="text-lg font-bold text-white tracking-tight">SAID</span>
            </div>
            <p class="text-xs text-white/30 mt-1 leading-tight">Spec-Aligned AI Development</p>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-5 space-y-0.5 overflow-y-auto sidebar-scroll">
            <p class="px-3 pt-1 pb-1.5 text-xs font-semibold text-white/25 uppercase tracking-widest">Main</p>

            @php
                $navActive = fn($route) => request()->is($route)
                    ? 'bg-teal/20 text-teal-light border-teal/30'
                    : 'text-white/60 hover:bg-white/5 hover:text-white border-transparent';
            @endphp

            <a href="/dashboard"
               class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium border {{ $navActive('dashboard') }} transition">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <p class="px-3 pt-4 pb-1.5 text-xs font-semibold text-white/25 uppercase tracking-widest">Workspace</p>

            <a href="#"
               class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium border {{ $navActive('projects*') }} transition">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                <span>Projects</span>
                <span class="ml-auto text-[11px] px-1.5 py-0.5 rounded-full bg-white/10 text-white/40 font-medium">soon</span>
            </a>

            <a href="#"
               class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium border {{ $navActive('agents*') }} transition">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>AI Agents</span>
                <span class="ml-auto text-[10px] px-1.5 py-0.5 rounded-full bg-white/10 text-white/40 font-medium">soon</span>
            </a>

            <p class="px-3 pt-4 pb-1.5 text-xs font-semibold text-white/25 uppercase tracking-widest">Settings</p>

            <a href="#"
               class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium border {{ $navActive('tokens*') }} transition">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                <span>API Tokens</span>
                <span class="ml-auto text-[10px] px-1.5 py-0.5 rounded-full bg-white/10 text-white/40 font-medium">soon</span>
            </a>
        </nav>

        {{-- User footer --}}
        <div class="border-t border-white/10 px-4 py-3">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-teal/20 text-teal-light flex items-center justify-center text-sm font-semibold shrink-0">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-white/40 truncate">{{ Auth::user()->email ?? '' }}</p>
                </div>
                <form action="/logout" method="POST" class="shrink-0">
                    @csrf
                    <button type="submit" title="Sign out"
                            class="text-white/20 hover:text-coral transition p-1 rounded">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main content --}}
    <main class="flex-1 min-w-0 bg-white">
        {{-- Top bar --}}
        <header class="bg-white border-b border-cool/30 px-8 py-2.5 flex items-center justify-between">
            <div>
                <h1 class="text-lg font-semibold text-navy">@yield('heading', 'Dashboard')</h1>
                @hasSection('subheading')
                    <p class="text-xs text-warm mt-0.5">@yield('subheading')</p>
                @endif
            </div>
            <div class="flex items-center gap-3">
                @yield('actions')
            </div>
        </header>

        {{-- Page content — max-width wrapper --}}
        <div class="max-w-[1280px] mx-auto px-8 py-6">
            @yield('content')
        </div>
    </main>

</body>
</html>
