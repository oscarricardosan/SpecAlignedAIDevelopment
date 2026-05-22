@extends("install.layout")

@section("step_description")
    @if ($post_install ?? false)
        The projects root folder is not accessible. Docker containers may need a restart.
    @else
        SAID needs to restart its Docker containers to mount your project root folder.
        This only takes a few seconds.
    @endif
@endsection

@section("content")

@php $postInstall = $post_install ?? false; @endphp

<h2 class="text-xl font-bold text-navy mb-2">
    @if ($postInstall)
        Projects folder not mounted
    @else
        Restart containers required
    @endif
</h2>
<p class="text-sm text-warm mb-6">
    The projects root folder is set to <code class="bg-slate-100 px-1.5 py-0.5 rounded text-navy font-mono text-xs">{{ $said_root }}</code>.
    Docker Compose must be restarted so the new volume is mounted at <code class="bg-slate-100 px-1.5 py-0.5 rounded text-navy font-mono text-xs">/said-projects</code>.
</p>

<div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
    <div class="flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
        </svg>
        <div>
            <p class="text-sm font-semibold text-amber-800 mb-1">Run this command on your host machine:</p>
            <code class="block bg-slate-900 text-green-400 text-sm px-4 py-3 rounded-lg font-mono mt-2">
                cd app && <br>
                docker compose down &&  <br>
                docker compose up -d
            </code>
            <p class="text-xs text-amber-800 mt-2">Docker Compose reads <code class="bg-amber-200 px-1 rounded font-mono text-xs">SAID_ROOT</code> from <code class="bg-amber-200 px-1 rounded font-mono text-xs">app/.env</code> automatically.</p>
        </div>
    </div>
</div>

<div class="bg-slate-50 rounded-lg p-4 mb-2">
    <p class="text-sm font-medium text-navy mb-2">Status</p>
    <div class="space-y-2">
        <div class="flex items-center gap-2 text-sm">
            <span>Projects folder mount <code class="bg-slate-100 px-1 rounded font-mono text-xs">/said-projects</code>:</span>
            @if ($mounted)
                <span class="text-teal font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Mounted
                </span>
            @else
                <span class="text-coral font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Not mounted yet
                </span>
            @endif
        </div>
    </div>
</div>

@if ($postInstall)
    <p class="text-sm text-warm mb-4">
        <a href="/install/storage" class="text-teal hover:underline font-medium">Go back to the storage setup</a>
        to reconfigure the path, then restart Docker Compose.
    </p>
    <p class="text-xs text-cool mb-6">
        Once the containers are back up, reload this page.
    </p>
    <form action="/dashboard" method="GET" class="mt-6">
        <button type="submit"
                class="w-full bg-teal text-white rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-teal-dark transition shadow-sm {{ $mounted ? '' : 'opacity-50 cursor-not-allowed' }}"
                {{ $mounted ? '' : 'disabled' }}>
            {{ $mounted ? 'Go to dashboard' : 'Restart containers first' }}
        </button>
    </form>
@else
    <form action="/install/restart" method="POST" class="mt-6">
        @csrf
        <button type="submit"
                class="w-full bg-teal text-white rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-teal-dark transition shadow-sm {{ $mounted ? '' : 'opacity-50 cursor-not-allowed' }}"
                {{ $mounted ? '' : 'disabled' }}>
            {{ $mounted ? 'Continue to admin user setup' : 'Restart containers first' }}
        </button>
    </form>
    <p class="text-xs text-warm mt-3 text-center">
        If the mount still doesn't work,
        <a href="/install/storage" class="text-teal hover:underline">go back to the storage step</a>
        and verify the path is correct.
    </p>
@endif

<p class="text-xs text-cool mt-3 text-center">
    The button will be enabled once <code class="bg-slate-100 px-1 rounded font-mono">/said-projects</code> is accessible.
</p>

@endsection
