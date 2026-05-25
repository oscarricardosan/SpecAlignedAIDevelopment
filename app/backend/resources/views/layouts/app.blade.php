<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAID — @yield('title', 'Dashboard')</title>
    <link rel="icon" type="image/png" href="/assets/favicon.png">
    <script src="https://cdn.tailwindcss.com?v=2"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@2/dist/iconify-icon.min.js"></script>
    <link rel="stylesheet" href="/css/said.css?v=9">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="min-h-screen flex text-navy"
      x-data="keyboardShortcuts()"
      @keydown.window="handle($event)">

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

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium border {{ $navActive('dashboard') }} transition duration-200">
                <iconify-icon icon="heroicons:home" class="w-4 h-4 shrink-0"></iconify-icon>
                Dashboard
            </a>

            <p class="px-3 pt-4 pb-1.5 text-xs font-semibold text-white/25 uppercase tracking-widest">Workspace</p>

            <a href="{{ route('projects.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium border {{ $navActive('projects*') }} transition duration-200">
                <iconify-icon icon="heroicons:folder" class="w-4 h-4 shrink-0"></iconify-icon>
                <span>Projects</span>
            </a>

            <a href="#"
               class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium border {{ $navActive('agents*') }} transition duration-200">
                <iconify-icon icon="heroicons:cpu-chip" class="w-4 h-4 shrink-0"></iconify-icon>
                <span>AI Agents</span>
                <span class="badge badge-xs badge-ghost text-white/40 ml-auto">soon</span>
            </a>

            <p class="px-3 pt-4 pb-1.5 text-xs font-semibold text-white/25 uppercase tracking-widest">Settings</p>

            <a href="#"
               class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium border {{ $navActive('tokens*') }} transition duration-200">
                <iconify-icon icon="heroicons:key" class="w-4 h-4 shrink-0"></iconify-icon>
                <span>API Tokens</span>
                <span class="badge badge-xs badge-ghost text-white/40 ml-auto">soon</span>
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
                <form action="{{ route('logout') }}" method="POST" class="shrink-0">
                    @csrf
                    <button type="submit"
                            class="tooltip tooltip-right text-white/20 hover:text-coral transition duration-200 p-1 rounded"
                            data-tip="Sign out">
                        <iconify-icon icon="heroicons:arrow-right-start-on-rectangle" class="w-4 h-4"></iconify-icon>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main content --}}
    <main class="flex-1 min-w-0 bg-white">
        {{-- Toast container --}}
        <div x-data="toastManager()" x-init="init()" class="fixed top-4 right-4 z-[100] flex flex-col gap-2 max-w-sm w-full pointer-events-none">
            <template x-for="(msg, idx) in messages" :key="idx">
                <div x-show="msg.show"
                     x-transition:enter="transition duration-300"
                     x-transition:enter-start="opacity-0 translate-x-8"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="pointer-events-auto">
                    <div role="alert" class="alert shadow-lg" :class="alertClass(msg.type)">
                        <iconify-icon :icon="iconForType(msg.type)" class="w-5 h-5 shrink-0"></iconify-icon>
                        <span x-text="msg.text"></span>
                        <button type="button" @click="dismiss(idx)" class="btn btn-ghost btn-xs btn-circle shrink-0">
                            <iconify-icon icon="heroicons:x-mark" class="w-3.5 h-3.5"></iconify-icon>
                        </button>
                    </div>
                </div>
            </template>
        </div>

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

    <script>
        function toastManager() {
            return {
                messages: [],
                init() {
                    const toast = {!! json_encode(session('toast')) !!};
                    console.log(toast);
                    @php session()->forget('toast') @endphp
                    if (toast) {
                        const isDuplicate = this.messages.some(m => m.text === toast.text && m.type === toast.type);
                        if (!isDuplicate) {
                            this.messages.push({ ...toast, show: true });
                            if (toast.type === 'success' || toast.type === 'info') {
                                setTimeout(() => {
                                    if (this.messages.length > 0) this.messages[0].show = false;
                                }, 5000);
                            }
                        }
                    }
                },
                alertClass(type) {
                    return {
                        'success': 'alert-success',
                        'error': 'alert-error',
                        'warning': 'alert-warning',
                        'info': 'alert-info',
                    }[type] || 'alert-info';
                },
                iconForType(type) {
                    return {
                        'success': 'heroicons:check-circle',
                        'error': 'heroicons:exclamation-circle',
                        'warning': 'heroicons:exclamation-triangle',
                        'info': 'heroicons:information-circle',
                    }[type] || 'heroicons:information-circle';
                },
                dismiss(idx) {
                    this.messages[idx].show = false;
                },
            };
        }

        function keyboardShortcuts() {
            return {
                handle(e) {
                    if (e.ctrlKey || e.metaKey) {
                        switch (e.key.toLowerCase()) {
                            case 'n':
                                e.preventDefault();
                                window.location.href = '{{ route('projects.create') }}';
                                break;
                            case 'd':
                                e.preventDefault();
                                window.location.href = '{{ route('dashboard') }}';
                                break;
                            case 'p':
                                e.preventDefault();
                                window.location.href = '{{ route('projects.index') }}';
                                break;
                        }
                    }
                },
            };
        }
    </script>

</body>
</html>
