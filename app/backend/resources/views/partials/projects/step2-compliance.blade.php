{{-- Step 2: Compliance & Audience --}}
<div x-show="step === 2">
    <h3 class="text-lg font-bold text-navy mb-1">Compliance &amp; audience</h3>
    <p class="text-sm text-warm mb-6">Define regulatory requirements and who will use this system.</p>

    <div class="space-y-8">
        {{-- Compliance --}}
        <fieldset>
            <legend class="text-sm font-semibold text-navy mb-3">Compliance requirements</legend>
            <p class="text-xs text-warm mb-3">Select all that apply. The AI will enforce relevant standards in generated code.</p>
            <div class="grid grid-cols-3 gap-3">
                @php
                $complianceIcons = [
                    'gdpr'      => 'heroicons:globe-europe-africa',
                    'hipaa'     => 'heroicons:heart',
                    'pci_dss'   => 'heroicons:credit-card',
                    'soc2'      => 'heroicons:cloud',
                    'iso_27001' => 'heroicons:document-check',
                ];
                $selectedCompliance = old('compliance', isset($project) ? $project->compliances->pluck('compliance')->toArray() : []);
                @endphp
                @foreach (\App\Support\Label::options('compliance') as $val => $info)
                    @if ($val !== 'none')
                    @php $desc = str_replace(\App\Support\Label::humanize($val) . ' — ', '', $info); @endphp
                <label class="flex flex-col items-center gap-2.5 p-4 border rounded-xl cursor-pointer transition duration-200 text-center"
                       :class="compliances.includes('{{ $val }}') ? 'border-teal/60 bg-teal/5 shadow-sm' : 'border-cool/40 hover:border-teal/50 hover:shadow-sm'">
                    <input type="checkbox" name="compliance[]" value="{{ $val }}" class="sr-only"
                           @change="toggleCompliance('{{ $val }}', $el.checked)"
                           {{ in_array($val, $selectedCompliance) ? 'checked' : '' }}>
                    <iconify-icon icon="{{ $complianceIcons[$val] ?? 'heroicons:shield-check' }}" style="font-size: 36px" class="shrink-0 pointer-events-none"
                                  :class="compliances.includes('{{ $val }}') ? 'text-teal' : 'text-cool'"></iconify-icon>
                    <div>
                        <span class="text-sm font-semibold text-navy">{{ \App\Support\Label::humanize($val) }}</span>
                        <p class="text-xs text-warm mt-0.5 max-w-[160px] leading-tight">{{ $desc }}</p>
                    </div>
                </label>
                    @endif
                @endforeach
            </div>
        @error('compliance')
            <p class="text-xs text-coral mt-2 flex items-center gap-1">
                <iconify-icon icon="heroicons:exclamation-circle" style="font-size: 14px" class="shrink-0"></iconify-icon>
                {{ $message }}
            </p>
        @enderror
    </fieldset>

    {{-- Target audience --}}
        <fieldset>
            <legend class="text-sm font-semibold text-navy mb-3">Target audience <span class="text-coral">*</span></legend>
            <div class="grid grid-cols-2 gap-3">
                @php
                $audienceIcons = [
                    'internal' => 'heroicons:building-office',
                    'b2b'      => 'heroicons:briefcase',
                    'b2c'      => 'heroicons:user-group',
                    'public'   => 'heroicons:globe-alt',
                ];
                $selectedAudience = old('target_audience', $project->target_audience ?? 'internal');
                @endphp
                @foreach (\App\Support\Label::options('target_audience') as $val => $info)
                <label class="flex items-start gap-3 p-4 border rounded-xl cursor-pointer transition duration-200"
                       :class="projectAudience === '{{ $val }}' ? 'border-teal/60 bg-teal/5 shadow-sm' : 'border-cool/40 hover:border-teal/50 hover:shadow-sm'">
                    <input type="radio" name="target_audience" value="{{ $val }}" x-model="projectAudience" class="sr-only"
                           {{ $selectedAudience === $val ? 'checked' : '' }}>
                    <iconify-icon icon="{{ $audienceIcons[$val] ?? 'heroicons:users' }}" style="font-size: 32px" class="shrink-0 pointer-events-none mt-0.5"
                                  :class="projectAudience === '{{ $val }}' ? 'text-teal' : 'text-cool'"></iconify-icon>
                    <div>
                        <span class="text-sm font-semibold text-navy">{{ \App\Support\Label::humanize($val) }}</span>
                        <p class="text-xs text-warm mt-0.5">{{ str_replace(\App\Support\Label::humanize($val) . ' — ', '', $info) }}</p>
                    </div>
                </label>
                @endforeach
            </div>
            @error('target_audience')
                <p class="text-xs text-coral mt-2 flex items-center gap-1">
                    <iconify-icon icon="heroicons:exclamation-circle" style="font-size: 14px" class="shrink-0"></iconify-icon>
                    {{ $message }}
                </p>
            @enderror
        </fieldset>
    </div>
</div>
