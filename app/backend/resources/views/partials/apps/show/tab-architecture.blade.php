@php
$archIcons = [
    'mvc'           => 'heroicons:square-3-stack-3d',
    'repository'    => 'heroicons:archive-box',
    'clean'         => 'heroicons:cube-transparent',
    'hexagonal'     => 'heroicons:view-columns',
    'microservices' => 'heroicons:server-stack',
    'monolith'      => 'heroicons:building-library',
    'serverless'    => 'heroicons:cloud-arrow-up',
    'event_driven'  => 'heroicons:bolt',
];
$archDescs = [
    'mvc'           => 'Separates data, UI, and logic into three interconnected components.',
    'repository'    => 'Abstracts data access behind interfaces for testability and swap.',
    'clean'         => 'Entities, use cases, adapters — business logic at the center.',
    'hexagonal'     => 'Domain isolated from I/O — ports define boundaries, adapters implement.',
    'microservices' => 'Independent deployable services, each with a single responsibility.',
    'monolith'      => 'Modular single deployable — simple ops, shared codebase.',
    'serverless'    => 'Function-as-a-service — no server management, scales to zero.',
    'event_driven'  => 'Events and message brokers decouple producers from consumers.',
];
$paradigmDescs = [
    'oop'        => 'Organizes code around objects that hold data and behavior.',
    'functional' => 'Pure functions, immutability — no side effects, predictable state.',
    'procedural' => 'Step-by-step instructions, procedures and routines.',
    'reactive'   => 'Streams and observables — data flows propagate changes automatically.',
    'hybrid'     => 'Mix of paradigms — pick the right tool for each problem.',
];
$principleDescs = \App\Support\Label::options('design_principle');
$testingIcons = [
    'phpunit'      => 'devicon:php',
    'pest'         => 'devicon:php',
    'jest'         => 'logos:jest',
    'vitest'       => 'logos:vitest',
    'pytest'       => 'logos:pytest',
    'xunit'        => 'devicon:csharp',
    'nunit'        => 'devicon:csharp',
    'cargo_test'   => 'devicon:rust',
    'go_test'      => 'devicon:go',
    'flutter_test' => 'devicon:flutter',
];
$styleDescs = [
    'psr12'     => 'PHP Standard Recommendation — coding style for PHP.',
    'pep8'      => 'Python Enhancement Proposal — style guide for Python.',
    'airbnb'    => 'Airbnb JavaScript Style Guide — widely adopted in React.',
    'standard'  => 'Standard JS — no config needed, zero semicolons.',
    'google_js' => 'Google JavaScript Style Guide.',
    'google_py' => 'Google Python Style Guide.',
    'rustfmt'   => 'Official Rust formatter — consistent, deterministic.',
    'gofmt'     => 'Go formatter — canonical style, non-negotiable.',
    'microsoft' => 'Microsoft C# coding conventions.',
];
$ciDescs = [
    'github_actions' => 'GitHub-native CI/CD — workflows in YAML.',
    'gitlab_ci'      => 'GitLab CI/CD — pipelines defined in .gitlab-ci.yml.',
    'jenkins'        => 'Extensible automation server — pipelines as code.',
    'circleci'       => 'Cloud-native CI/CD — fast, configurable.',
    'travis'         => 'Distributed CI for open source.',
    'azure_devops'   => 'Microsoft Azure Pipelines — cloud and on-prem.',
];
@endphp

<div x-show="tab === 'architecture'" class="card bg-base-100 border border-cool/50 shadow-sm">
    <div class="card-body p-5">
        <div class="flex gap-6">
            {{-- Left column --}}
            <div class="flex-1 min-w-0 space-y-4">
                {{-- Architecture & Paradigm --}}
                <div>
                    <p class="text-xs text-cool uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <iconify-icon icon="heroicons:square-3-stack-3d" style="font-size: 16px" class="text-teal"></iconify-icon>
                        Structure
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        {{-- Architecture Pattern --}}
                        <div class="bg-white rounded-xl border border-cool/20 p-4 flex items-start gap-3">
                            <iconify-icon icon="{{ $archIcons[$application->architecture] ?? 'heroicons:question-mark-circle' }}" style="font-size: 28px" class="text-teal shrink-0 mt-0.5"></iconify-icon>
                            <div>
                                <p class="text-sm font-semibold text-navy">{{ \App\Support\Label::humanize($application->architecture) }}</p>
                                <p class="text-xs text-warm mt-0.5 leading-relaxed">{{ $archDescs[$application->architecture] ?? '' }}</p>
                            </div>
                        </div>
                        {{-- Paradigm --}}
                        <div class="bg-white rounded-xl border border-cool/20 p-4 flex items-start gap-3">
                            <iconify-icon icon="heroicons:chevron-right" style="font-size: 28px" class="text-teal shrink-0 mt-0.5"></iconify-icon>
                            <div>
                                <p class="text-sm font-semibold text-navy">{{ \App\Support\Label::humanize($application->paradigm) }}</p>
                                <p class="text-xs text-warm mt-0.5 leading-relaxed">{{ $paradigmDescs[$application->paradigm] ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Design Principles --}}
                @if ($application->design_principles_codes)
                <div>
                    <p class="text-xs text-cool uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <iconify-icon icon="heroicons:light-bulb" style="font-size: 16px" class="text-teal"></iconify-icon>
                        Design principles
                    </p>
                    <div class="space-y-2.5">
                        @foreach ($application->design_principles_codes as $code)
                        @php
                            $fullDesc = $principleDescs[$code] ?? '';
                            $descParts = explode(' — ', $fullDesc, 2);
                            $descText = $descParts[1] ?? $fullDesc;
                        @endphp
                        <div class="bg-white rounded-xl border border-cool/20 p-3.5 flex items-start gap-3">
                            <iconify-icon icon="heroicons:check-circle" style="font-size: 22px" class="text-teal shrink-0 mt-0.5"></iconify-icon>
                            <div>
                                <span class="text-sm font-semibold text-navy">{{ \App\Support\Label::humanize($code) }}</span>
                                <p class="text-xs text-warm mt-0.5 leading-relaxed">{{ $descText }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Code quality --}}
                <div>
                    <p class="text-xs text-cool uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <iconify-icon icon="heroicons:shield-check" style="font-size: 16px" class="text-teal"></iconify-icon>
                        Code quality
                    </p>

                    {{-- Testing + Code style + CI/CD row --}}
                    <div class="grid grid-cols-3 gap-4">
                        {{-- Testing frameworks --}}
                        <div class="bg-white rounded-xl border border-cool/20 p-4">
                            <p class="text-xs text-cool uppercase tracking-wider mb-3 flex items-center gap-1.5">
                                <iconify-icon icon="heroicons:beaker" style="font-size: 16px" class="text-coral"></iconify-icon>
                                Testing framework
                            </p>
                            @if ($application->testing_frameworks_codes)
                            @php $tf = $application->testing_frameworks_codes; @endphp
                            <div class="flex flex-col items-center gap-2.5 p-4 ">
                                <span class="w-10 h-10 flex items-center justify-centeApr shrink-0">
                                    <iconify-icon icon="{{ $testingIcons[$tf] ?? 'heroicons:beaker' }}" style="font-size: 36px" class="text-teal"></iconify-icon>
                                </span>
                                <span class="text-sm font-semibold text-navy">{{ \App\Support\Label::humanize($tf) }}</span>
                            </div>
                            @else
                            <p class="text-sm font-medium text-cool">—</p>
                            @endif
                        </div>
                        <div class="bg-white rounded-xl border border-cool/20 p-4">
                            <p class="text-xs text-cool uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <iconify-icon icon="heroicons:document-text" style="font-size: 18px" class="text-teal"></iconify-icon>
                                Code style
                            </p>
                            @if ($application->code_style)
                            <p class="text-sm font-semibold text-navy">{{ \App\Support\Label::humanize($application->code_style) }}</p>
                            <p class="text-xs text-warm mt-0.5">{{ $styleDescs[$application->code_style] ?? '' }}</p>
                            @else
                            <p class="text-sm font-medium text-cool">—</p>
                            @endif
                        </div>
                        <div class="bg-white rounded-xl border border-cool/20 p-4">
                            <p class="text-xs text-cool uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <iconify-icon icon="heroicons:arrow-path" style="font-size: 18px" class="text-teal"></iconify-icon>
                                CI / CD
                            </p>
                            @if ($application->ci_cd)
                            <p class="text-sm font-semibold text-navy">{{ \App\Support\Label::humanize($application->ci_cd) }}</p>
                            <p class="text-xs text-warm mt-0.5">{{ $ciDescs[$application->ci_cd] ?? '' }}</p>
                            @else
                            <p class="text-sm font-medium text-cool">—</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar: Package Manager + Executor --}}
            <div class="w-56 shrink-0 space-y-3">
                @if ($application->package_manager)
                <div class="bg-white rounded-xl border border-cool/20 p-4">
                    <p class="text-[10px] text-cool uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <iconify-icon icon="heroicons:archive-box" style="font-size: 16px" class="text-teal shrink-0"></iconify-icon>
                        Package manager
                    </p>
                    <p class="text-sm font-semibold text-navy">{{ $application->package_manager }}</p>
                </div>
                @endif
                @if ($application->executor)
                @php
                $executorIcons = ['local' => 'heroicons:computer-desktop', 'docker' => 'devicon:docker'];
                $executorLabels = ['local' => 'Local', 'docker' => 'Docker'];
                $executorDescs = ['local' => 'Commands run directly on the host machine.', 'docker' => 'Commands run inside ephemeral containers.'];
                @endphp
                <div class="bg-white rounded-xl border border-cool/20 p-4">
                    <p class="text-[10px] text-cool uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <iconify-icon icon="heroicons:play-circle" style="font-size: 16px" class="text-teal shrink-0"></iconify-icon>
                        Execution environment
                    </p>
                    <div class="flex items-start gap-2.5">
                        <iconify-icon icon="{{ $executorIcons[$application->executor] ?? 'heroicons:play-circle' }}" style="font-size: 22px" class="text-navy shrink-0 mt-0.5"></iconify-icon>
                        <div>
                            <p class="text-sm font-semibold text-navy">{{ $executorLabels[$application->executor] ?? ucfirst($application->executor) }}</p>
                            <p class="text-xs text-warm mt-0.5 leading-relaxed">{{ $executorDescs[$application->executor] ?? '' }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
