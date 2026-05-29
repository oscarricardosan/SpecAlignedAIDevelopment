<div x-show="step === 5">
    <h3 class="text-lg font-bold text-navy mb-1">Architecture &amp; principles</h3>
    <p class="text-sm text-warm mb-6">How should the code be structured and what rules should the AI follow?</p>
    <div class="space-y-6">
        <div>
            <label class="block text-sm font-semibold text-navy mb-3">Architecture pattern <span class="text-coral">*</span></label>
            <div class="grid grid-cols-2 gap-3">
                <template x-for="a in architectureOptions()" :key="a.value">
                    <label class="flex items-start gap-3 p-4 border rounded-xl cursor-pointer transition duration-200"
                           :class="architecture === a.value ? 'border-teal/60 bg-teal/5 shadow-sm' : 'border-cool/40 hover:border-teal/50 hover:shadow-sm'">
                        <input type="radio" name="architecture" :value="a.value" x-model="architecture" class="sr-only">
                        <iconify-icon :icon="a.icon" style="font-size: 28px" class="shrink-0 pointer-events-none mt-0.5"
                                      :class="architecture === a.value ? 'text-teal' : 'text-cool'"></iconify-icon>
                        <div>
                            <span class="text-sm font-semibold text-navy" x-text="a.label"></span>
                            <p class="text-xs text-warm mt-0.5 leading-relaxed" x-text="a.desc"></p>
                        </div>
                    </label>
                </template>
            </div>
            @error('architecture')
                <p class="text-xs text-coral mt-2 flex items-center gap-1">
                    <iconify-icon icon="heroicons:exclamation-circle" style="font-size: 14px" class="shrink-0"></iconify-icon>
                    {{ $message }}
                </p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-navy mb-3">Programming paradigm <span class="text-coral">*</span></label>
            <div class="grid grid-cols-2 gap-3">
                <template x-for="p in paradigmOptions()" :key="p.value">
                    <label class="flex items-start gap-3 p-4 border rounded-xl cursor-pointer transition duration-200 text-sm text-navy"
                           :class="paradigm === p.value ? 'border-teal/60 bg-teal/5 shadow-sm' : 'border-cool/40 hover:border-teal/50 hover:shadow-sm'">
                        <input type="radio" name="paradigm" :value="p.value" class="sr-only"
                               x-model="paradigm">
                        <iconify-icon icon="heroicons:chevron-right" style="font-size: 16px" class="shrink-0 pointer-events-none mt-0.5"
                                      :class="paradigm === p.value ? 'text-teal' : 'text-cool/40'"></iconify-icon>
                        <span x-text="p.label"></span>
                    </label>
                </template>
            </div>
            @error('paradigm')
                <p class="text-xs text-coral mt-1 flex items-center gap-1">
                    <iconify-icon icon="heroicons:exclamation-circle" style="font-size: 14px" class="shrink-0"></iconify-icon>
                    {{ $message }}
                </p>
            @enderror
        </div>
        <fieldset>
            <legend class="text-sm font-semibold text-navy mb-3">Design principles</legend>
            <div class="grid grid-cols-2 gap-3">
                @php $selectedPrinciples = old('design_principles', []); @endphp
                @foreach (\App\Support\Label::options('design_principle') as $val => $info)
                <label class="flex items-start gap-2 p-3 border border-cool/40 rounded-lg cursor-pointer hover:border-teal/50 transition duration-200 text-sm text-navy has-[:checked]:border-teal/60 has-[:checked]:bg-teal/5">
                    <input type="checkbox" name="design_principles[]" value="{{ $val }}" class="checkbox checkbox-sm border-cool text-teal focus:ring-teal mt-0.5"
                           {{ in_array($val, $selectedPrinciples) ? 'checked' : '' }}>
                    <div>
                        <span class="font-semibold">{{ \App\Support\Label::humanize($val) }}</span>
                        <span class="text-xs text-warm ml-1">— {{ $info }}</span>
                    </div>
                </label>
                @endforeach
            </div>
        </fieldset>
    </div>
</div>
