{{-- Step 1: Business Context --}}
<div x-show="step === 1">
    <h3 class="text-lg font-bold text-navy mb-1">Business context</h3>
    <p class="text-sm text-warm mb-6">This information gives AI agents domain awareness when writing specs and code.</p>

    <div class="space-y-8">
        {{-- System criticality --}}
        <div>
            <label class="block text-sm font-semibold text-navy mb-3">System criticality <span class="text-coral">*</span></label>
            <div class="grid grid-cols-2 gap-3">
                @foreach (\App\Support\Label::options('criticality') as $val => $label)
                <label class="flex items-start gap-3 p-4 border rounded-xl cursor-pointer transition duration-200"
                       :class="projectCriticality === '{{ $val }}' ? 'border-teal/60 bg-teal/5 shadow-sm' : 'border-cool/40 hover:border-teal/50 hover:shadow-sm'">
                    <input type="radio" name="criticality" value="{{ $val }}" x-model="projectCriticality" class="sr-only"
                           {{ old('criticality', $project->criticality ?? '') === $val ? 'checked' : '' }}>
                    <div>
                        <span class="text-sm font-semibold text-navy">{{ \App\Support\Label::humanize($val) }}</span>
                        <p class="text-xs text-warm mt-0.5">{{ str_replace(\App\Support\Label::humanize($val) . ' — ', '', $label) }}</p>
                    </div>
                </label>
                @endforeach
            </div>
            @error('criticality')
                <p class="text-xs text-coral mt-2 flex items-center gap-1">
                    <iconify-icon icon="heroicons:exclamation-circle" style="font-size: 14px" class="shrink-0"></iconify-icon>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Business sector --}}
        <div>
            <label class="block text-sm font-semibold text-navy mb-3">Business sector <span class="text-coral">*</span></label>
            <div class="grid grid-cols-3 gap-3">
                @php
                $sectorIcons = [
                    'finance'       => 'heroicons:banknotes',
                    'healthcare'    => 'heroicons:heart',
                    'education'     => 'heroicons:academic-cap',
                    'ecommerce'     => 'heroicons:shopping-cart',
                    'logistics'     => 'heroicons:truck',
                    'government'    => 'heroicons:building-library',
                    'entertainment' => 'heroicons:film',
                    'manufacturing' => 'heroicons:cog-8-tooth',
                    'other'         => 'heroicons:ellipsis-horizontal-circle',
                ];
                @endphp
                @foreach (\App\Support\Label::options('business_sector') as $val => $label)
                <label class="flex flex-col items-center gap-2.5 p-4 border rounded-xl cursor-pointer transition duration-200 text-center"
                       :class="projectSector === '{{ $val }}' ? 'border-teal/60 bg-teal/5 shadow-sm' : 'border-cool/40 hover:border-teal/50 hover:shadow-sm'">
                    <input type="radio" name="business_sector" value="{{ $val }}" x-model="projectSector" class="sr-only"
                           {{ old('business_sector', $project->business_sector ?? '') === $val ? 'checked' : '' }}>
                    <iconify-icon icon="{{ $sectorIcons[$val] ?? 'heroicons:briefcase' }}" style="font-size: 36px" class="shrink-0 pointer-events-none"
                                  :class="projectSector === '{{ $val }}' ? 'text-teal' : 'text-cool'"></iconify-icon>
                    <span class="text-sm font-semibold text-navy leading-tight">{{ str_replace(' — ', '<br>', \App\Support\Label::humanize($val)) }}</span>
                </label>
                @endforeach
            </div>
            @error('business_sector')
                <p class="text-xs text-coral mt-2 flex items-center gap-1">
                    <iconify-icon icon="heroicons:exclamation-circle" style="font-size: 14px" class="shrink-0"></iconify-icon>
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>
</div>
