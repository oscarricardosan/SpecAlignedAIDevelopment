<div x-show="tab === 'identity'" class="card bg-base-100 border border-cool/50 shadow-sm">
    <div class="card-body p-5">
        {{-- Location --}}
        <div>
            <p class="text-xs text-cool uppercase tracking-wider mb-3 flex items-center gap-1.5">
                <iconify-icon icon="heroicons:folder-open" style="font-size: 16px" class="text-teal"></iconify-icon>
                Location
            </p>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-xl border border-cool/20 p-4 flex items-start gap-3">
                    <iconify-icon icon="heroicons:folder" style="font-size: 24px" class="text-peach shrink-0 mt-0.5"></iconify-icon>
                    <div class="min-w-0">
                        <p class="text-[10px] text-cool uppercase tracking-wider">Path</p>
                        <p class="text-sm font-mono text-navy mt-0.5 break-all">{{ $application->path }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-cool/20 p-4 flex items-start gap-3">
                    <iconify-icon icon="heroicons:server-stack" style="font-size: 24px" class="text-teal shrink-0 mt-0.5"></iconify-icon>
                    <div class="min-w-0">
                        <p class="text-[10px] text-cool uppercase tracking-wider">Disk</p>
                        <p class="text-sm font-mono text-navy mt-0.5">{{ $application->disk }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        @if ($application->notes)
        <div class="mt-5">
            <p class="text-xs text-cool uppercase tracking-wider mb-3 flex items-center gap-1.5">
                <iconify-icon icon="heroicons:pencil-square" style="font-size: 16px" class="text-teal"></iconify-icon>
                Notes
            </p>
            <div class="bg-white rounded-xl border border-cool/20 p-4">
                <p class="text-sm text-warm whitespace-pre-wrap leading-relaxed">{{ $application->notes }}</p>
            </div>
        </div>
        @endif

        {{-- AI Context --}}
        @if ($application->context_architecture || $application->context_stack || $application->context_guidelines)
        <div class="mt-5">
            <p class="text-xs text-cool uppercase tracking-wider mb-3 flex items-center gap-1.5">
                <iconify-icon icon="heroicons:document-text" style="font-size: 16px" class="text-teal"></iconify-icon>
                AI context
            </p>
            <div class="border border-cool/30 rounded-xl overflow-hidden" x-data="{ ctxTab: 'architecture' }">
                <div class="flex border-b border-cool/20 bg-cool/10">
                    <button type="button" @click="ctxTab = 'architecture'"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold transition duration-200 border-b-2"
                            :class="ctxTab === 'architecture' ? 'text-teal border-teal bg-white' : 'text-cool border-transparent hover:text-navy'">
                        <iconify-icon icon="heroicons:square-3-stack-3d" style="font-size: 16px" class="inline-block mr-1.5 align-text-bottom"></iconify-icon>
                        Architecture
                    </button>
                    <button type="button" @click="ctxTab = 'stack'"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold transition duration-200 border-b-2"
                            :class="ctxTab === 'stack' ? 'text-teal border-teal bg-white' : 'text-cool border-transparent hover:text-navy'">
                        <iconify-icon icon="heroicons:cube" style="font-size: 16px" class="inline-block mr-1.5 align-text-bottom"></iconify-icon>
                        Stack
                    </button>
                    <button type="button" @click="ctxTab = 'guidelines'"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold transition duration-200 border-b-2"
                            :class="ctxTab === 'guidelines' ? 'text-teal border-teal bg-white' : 'text-cool border-transparent hover:text-navy'">
                        <iconify-icon icon="heroicons:clipboard-document-list" style="font-size: 16px" class="inline-block mr-1.5 align-text-bottom"></iconify-icon>
                        Conventions
                    </button>
                </div>
                <div class="p-4">
                    <div x-show="ctxTab === 'architecture'" x-cloak>
                        <textarea rows="5" disabled
                                  class="textarea textarea-bordered w-full bg-white text-sm text-navy resize-none">@if($application->context_architecture){{ $application->context_architecture }}@endif</textarea>
                    </div>
                    <div x-show="ctxTab === 'stack'" x-cloak>
                        <textarea rows="5" disabled
                                  class="textarea textarea-bordered w-full bg-white text-sm text-navy resize-none">@if($application->context_stack){{ $application->context_stack }}@endif</textarea>
                    </div>
                    <div x-show="ctxTab === 'guidelines'" x-cloak>
                        <textarea rows="5" disabled
                                  class="textarea textarea-bordered w-full bg-white text-sm text-navy resize-none">@if($application->context_guidelines){{ $application->context_guidelines }}@endif</textarea>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
