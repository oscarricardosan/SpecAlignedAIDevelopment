{{-- RIGHT: Summary sidebar --}}
<div class="w-64 shrink-0 hidden lg:block">
    <div class="sticky top-6">
        <div class="card bg-base-100 border border-cool/50 shadow-sm">
            <div class="card-body p-5 text-center">

                <p class="text-xs font-semibold text-cool uppercase tracking-wider mb-4">Summary</p>

                {{-- Tech icon BIG --}}
                <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 shrink-0"
                     :class="tech ? 'bg-teal/10' : 'bg-slate-100'">
                    <iconify-icon :icon="techIcon() || 'heroicons:code-bracket'" style="font-size: 40px"
                                  :class="tech ? 'text-teal' : 'text-cool'"></iconify-icon>
                </div>

                <h4 class="text-base font-bold text-navy mb-4" x-text="techLabel() || 'New Application'"></h4>

                <div class="space-y-2.5 text-sm text-left">
                    <div class="flex justify-between gap-1" x-show="platform">
                        <span class="text-cool shrink-0">Platform</span>
                        <span class="font-semibold text-navy truncate" x-text="platformLabel()"></span>
                    </div>
                    <div class="flex justify-between gap-1" x-show="language">
                        <span class="text-cool shrink-0">Language</span>
                        <span class="font-semibold text-navy truncate">
                            <span x-text="languageLabel()"></span>
                            <span x-show="language !== 'other' && languageVersion && languageVersion !== 'custom'" class="text-cool font-normal" x-text="' ' + languageVersion"></span>
                            <span x-show="language !== 'other' && languageVersion === 'custom' && languageVersionCustom" class="text-cool font-normal" x-text="' ' + languageVersionCustom"></span>
                        </span>
                    </div>
                    <div class="flex justify-between gap-1" x-show="tech">
                        <span class="text-cool shrink-0">Framework</span>
                        <span class="font-semibold text-navy truncate">
                            <span x-text="techLabel()"></span>
                            <span x-show="frameworkVersion && frameworkVersion !== 'custom'" class="text-cool font-normal" x-text="' ' + frameworkVersion"></span>
                            <span x-show="frameworkVersion === 'custom' && frameworkVersionCustom" class="text-cool font-normal" x-text="' ' + frameworkVersionCustom"></span>
                        </span>
                    </div>
                    <div class="flex justify-between gap-1" x-show="paradigm">
                        <span class="text-cool shrink-0">Paradigm</span>
                        <span class="font-semibold text-navy truncate" x-text="paradigmShortLabel()"></span>
                    </div>
                    <div class="flex justify-between gap-1" x-show="architecture">
                        <span class="text-cool shrink-0">Architecture</span>
                        <span class="font-semibold text-navy truncate" x-text="architectureShortLabel()"></span>
                    </div>
                    <div x-show="databases.length > 0">
                        <span class="text-cool text-sm">Databases</span>
                        <div class="flex flex-wrap gap-1 mt-1">
                            <template x-for="db in databases" :key="db">
                                <span class="badge badge-sm badge-ghost text-[11px]" x-text="databaseLabel(db)"></span>
                            </template>
                        </div>
                    </div>
                    <div class="flex justify-between" x-show="executor">
                        <span class="text-cool">Executor</span>
                        <span class="font-semibold text-navy capitalize" x-text="executor"></span>
                    </div>
                    <div class="flex justify-between" x-show="packageManager">
                        <span class="text-cool">Packages</span>
                        <span class="font-semibold text-navy" x-text="packageManager"></span>
                    </div>
                </div>

                <template x-if="selectedPrinciples().length">
                    <div class="mt-4 pt-4 border-t border-cool/20 text-left">
                        <p class="text-[10px] text-cool uppercase tracking-wider mb-2">Principles</p>
                        <div class="flex flex-wrap gap-1">
                            <template x-for="p in selectedPrinciples()" :key="p">
                                <span class="badge badge-sm badge-ghost text-[10px]" x-text="p"></span>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
