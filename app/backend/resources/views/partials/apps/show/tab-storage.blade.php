@php
$dbIcons = [
    'postgresql' => 'devicon:postgresql',
    'mysql'      => 'devicon:mysql',
    'sqlite'     => 'devicon:sqlite',
    'sqlserver'  => 'devicon:microsoftsqlserver',
    'oracle'     => 'devicon:oracle',
    'mongodb'    => 'devicon:mongodb',
];
$storageIcons = [
    'local'      => 'heroicons:computer-desktop',
    's3'         => 'simple-icons:amazons3',
    's3_compat'  => 'heroicons:cloud',
    'ftp'        => 'heroicons:arrow-up-tray',
    'sftp'       => 'heroicons:arrow-up-tray',
    'azure_blob' => 'simple-icons:azuredevops',
    'gcs'        => 'simple-icons:googlecloud',
];
$repoIcons = [
    'github'    => 'simple-icons:github',
    'gitlab'    => 'simple-icons:gitlab',
    'gitea'     => 'simple-icons:gitea',
    'bitbucket' => 'simple-icons:bitbucket',
    'other'     => 'heroicons:ellipsis-horizontal-circle',
];
@endphp

<div x-show="tab === 'storage'" class="card bg-base-100 border border-cool/50 shadow-sm">
    <div class="card-body p-5">
        <h3 class="text-base font-semibold text-navy mb-1">Database &amp; file storage</h3>
        <p class="text-sm text-warm mb-6">How data and files are persisted.</p>

        {{-- Database engine --}}
        @if ($application->databases_codes)
        <div class="mb-6">
            <h4 class="text-xs text-cool uppercase tracking-wider mb-3 flex items-center gap-1.5">
                <iconify-icon icon="heroicons:circle-stack" style="font-size: 16px" class="text-teal"></iconify-icon>
                Database engine
            </h4>
            <div class="grid grid-cols-4 gap-3">
                @foreach ($application->databases_codes as $code)
                <div class="flex flex-col items-center gap-2.5 p-4 border border-cool/20 rounded-xl text-center">
                    <iconify-icon icon="{{ $dbIcons[$code] ?? 'heroicons:circle-stack' }}" style="font-size: 40px" class="text-teal"></iconify-icon>
                    <span class="text-sm font-semibold text-navy leading-tight">{{ \App\Support\Label::humanize($code) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Data access style --}}
        @if ($application->database_access)
        <div class="mb-6">
            <h4 class="text-xs text-cool uppercase tracking-wider mb-3 flex items-center gap-1.5">
                <iconify-icon icon="heroicons:arrows-pointing-in" style="font-size: 16px" class="text-teal"></iconify-icon>
                Data access style
            </h4>
            <div class="bg-white rounded-xl border border-cool/20 p-4 flex items-start gap-3 max-w-lg">
                <iconify-icon icon="heroicons:chevron-right" style="font-size: 20px" class="text-teal shrink-0 mt-0.5"></iconify-icon>
                <p class="text-sm font-semibold text-navy">{{ \App\Support\Label::humanize($application->database_access) }}</p>
            </div>
        </div>
        @endif

        {{-- File storage --}}
        @if ($application->storage_codes)
        <div class="mb-6">
            <h4 class="text-xs text-cool uppercase tracking-wider mb-3 flex items-center gap-1.5">
                <iconify-icon icon="heroicons:folder-open" style="font-size: 16px" class="text-teal"></iconify-icon>
                File storage
            </h4>
            <div class="grid grid-cols-4 gap-3">
                @foreach ($application->storage_codes as $code)
                <div class="flex flex-col items-center gap-2.5 p-4 border border-cool/20 rounded-xl text-center">
                    <iconify-icon icon="{{ $storageIcons[$code] ?? 'heroicons:folder' }}" style="font-size: 40px" class="text-teal"></iconify-icon>
                    <span class="text-sm font-semibold text-navy leading-tight">{{ \App\Support\Label::humanize($code) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Code repository --}}
        @if ($application->code_repository)
        <div>
            <h4 class="text-xs text-cool uppercase tracking-wider mb-3 flex items-center gap-1.5">
                <iconify-icon icon="heroicons:code-bracket-square" style="font-size: 16px" class="text-teal"></iconify-icon>
                Code repository
            </h4>
            <div class="flex flex-col items-center gap-2.5 p-4 border border-cool/20 rounded-xl text-center w-28">
                <iconify-icon icon="{{ $repoIcons[$application->code_repository] ?? 'heroicons:code-bracket' }}" style="font-size: 40px" class="text-teal"></iconify-icon>
                <span class="text-sm font-semibold text-navy leading-tight">{{ \App\Support\Label::humanize($application->code_repository) }}</span>
            </div>
            @if ($application->code_repository_url)
            <div class="mt-4 max-w-lg">
                <p class="text-[10px] text-cool uppercase tracking-wider mb-1">Repository URL</p>
                <a href="{{ $application->code_repository_url }}" target="_blank" rel="noopener" class="text-sm text-teal hover:underline break-all">{{ $application->code_repository_url }}</a>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
