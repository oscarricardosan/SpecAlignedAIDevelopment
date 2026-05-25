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

<div role="alert" class="alert alert-warning mb-6 text-sm">
    <iconify-icon icon="heroicons:exclamation-triangle" class="w-5 h-5"></iconify-icon>
    <div>
        <p class="font-semibold mb-1">Run this command on your host machine:</p>
        <code class="block bg-slate-900 text-green-400 text-sm px-4 py-3 rounded-lg font-mono mt-2">
            cd app && <br>
            docker compose down &&  <br>
            docker compose up -d
        </code>
        <p class="text-xs mt-2">Docker Compose reads <code class="bg-amber-200 px-1 rounded font-mono text-xs">SAID_ROOT</code> from <code class="bg-amber-200 px-1 rounded font-mono text-xs">app/.env</code> automatically.</p>
    </div>
</div>

<div class="bg-slate-50 rounded-lg p-4 mb-2">
    <p class="text-sm font-medium text-navy mb-3 flex items-center gap-2">
        <iconify-icon icon="heroicons:server-stack" class="w-4 h-4 text-teal"></iconify-icon>
        Status
    </p>
    <div class="space-y-2">
        <div class="flex items-center gap-2 text-sm">
            <span>Projects folder mount <code class="bg-slate-100 px-1 rounded font-mono text-xs">/said-projects</code>:</span>
            @if ($mounted)
                <span class="badge badge-success gap-1 text-xs">
                    <iconify-icon icon="heroicons:check" class="w-3.5 h-3.5"></iconify-icon>
                    Mounted
                </span>
            @else
                <span class="badge badge-error gap-1 text-xs">
                    <iconify-icon icon="heroicons:x-mark" class="w-3.5 h-3.5"></iconify-icon>
                    Not mounted yet
                </span>
            @endif
        </div>
    </div>
</div>

@if ($postInstall)
    <p class="text-sm text-warm mb-4">
        <a href="{{ route('install.storage') }}" class="link link-primary">Go back to the storage setup</a>
        to reconfigure the path, then restart Docker Compose.
    </p>
    <p class="text-xs text-cool mb-6">
        Once the containers are back up, reload this page.
    </p>
    <form action="{{ route('dashboard') }}" method="GET" class="mt-6">
        <button type="submit" class="btn btn-primary w-full">
            <iconify-icon icon="heroicons:arrow-path" class="w-4 h-4"></iconify-icon>
            Reload page
        </button>
    </form>
@else
    @if (!$mounted)
        <p class="text-sm text-warm font-medium text-center mb-4">
            Restart containers first, then reload this page.
        </p>
    @endif

    @if ($mounted)
        <form action="{{ route('install.restart.save') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary w-full">
                Continue to admin user setup
            </button>
        </form>
    @else
        <a href="{{ route('install.restart') }}"
           class="btn btn-primary w-full">
            <iconify-icon icon="heroicons:arrow-path" class="w-4 h-4"></iconify-icon>
            Reload page
        </a>
    @endif

    <p class="text-xs text-warm mt-3 text-center">
        If the mount still doesn't work,
        <a href="{{ route('install.storage') }}" class="link link-primary">go back to the storage step</a>
        and verify the path is correct.
    </p>
@endif

@endsection
