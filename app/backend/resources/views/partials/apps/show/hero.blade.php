@php
$platformIcons = [
    'web'     => ['heroicons:globe-alt', 'Browser App', 'Full web app with HTML, CSS, UI.'],
    'api'     => ['heroicons:server-stack', 'API Backend', 'Headless — JSON, gRPC, GraphQL. No views.'],
    'mobile'  => ['heroicons:device-phone-mobile', 'Mobile App', 'iOS / Android. Pairs with a backend.'],
    'desktop' => ['heroicons:computer-desktop', 'Desktop App', 'Native desktop application.'],
    'cli'     => ['heroicons:command-line', 'CLI Tool', 'Terminal I/O, no UI.'],
];
$langIcons = [
    'php'         => 'devicon:php',
    'javascript'  => 'devicon:javascript',
    'typescript'  => 'devicon:typescript',
    'python'      => 'devicon:python',
    'csharp'      => 'devicon:csharp',
    'rust'        => 'devicon:rust',
    'go'          => 'devicon:go',
    'dart'        => 'devicon:dart',
    'deno'        => 'devicon:denojs',
];
$techLabel = \App\Support\Label::humanize($application->technology);
if (str_starts_with($application->technology, 'vanilla_')) {
    $techLabel = 'No framework';
}
$platInfo = $platformIcons[$application->platform] ?? ['heroicons:computer-desktop', \App\Support\Label::humanize($application->platform), ''];
@endphp

<div class="card bg-base-100 border border-cool/50 shadow-sm">
    <div class="card-body p-5">
        {{-- Stack meta cards --}}
        <div class="grid grid-cols-3 gap-3">
            {{-- Language --}}
            <div class="bg-white rounded-xl border border-cool/20 p-4 flex items-start gap-3">
                <iconify-icon icon="{{ $langIcons[$application->language] ?? 'heroicons:code-bracket' }}" style="font-size: 28px" class="text-navy shrink-0 mt-0.5"></iconify-icon>
                <div class="min-w-0">
                    <p class="text-xs text-cool uppercase tracking-wider mb-1">Language</p>
                    <p class="text-sm font-semibold text-navy">
                        {{ $application->language_is_custom ? $application->language : \App\Support\Label::humanize($application->language) }}
                        @if ($application->language_version)
                        <span class="text-xs text-warm font-normal"> · v{{ $application->language_version }}</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Platform type --}}
            <div class="bg-white rounded-xl border border-cool/20 p-4 flex items-start gap-3">
                <iconify-icon icon="{{ $platInfo[0] }}" style="font-size: 28px" class="text-teal shrink-0 mt-0.5"></iconify-icon>
                <div class="min-w-0">
                    <p class="text-xs text-cool uppercase tracking-wider mb-1">Platform</p>
                    <p class="text-sm font-semibold text-navy">{{ $platInfo[1] }}</p>
                    <p class="text-xs text-warm mt-0.5 leading-relaxed">{{ $platInfo[2] }}</p>
                </div>
            </div>

            {{-- Framework --}}
            <div class="bg-white rounded-xl border border-cool/20 p-4 flex items-start gap-3">
                <iconify-icon icon="heroicons:cube" style="font-size: 28px" class="text-peach shrink-0 mt-0.5"></iconify-icon>
                <div class="min-w-0">
                    <p class="text-xs text-cool uppercase tracking-wider mb-1">Framework</p>
                    <p class="text-sm font-semibold text-navy truncate">
                        {{ $techLabel }}
                        @if ($application->framework_version)
                        <span class="text-xs text-warm font-normal"> · v{{ $application->framework_version }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Description --}}
        @if ($application->description)
        <p class="text-sm text-warm mt-4 leading-relaxed">{{ $application->description }}</p>
        @endif
    </div>
</div>
