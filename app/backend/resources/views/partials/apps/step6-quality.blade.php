<div x-show="step === 6">
    <h3 class="text-lg font-bold text-navy mb-1">Testing, CI/CD &amp; style</h3>
    <p class="text-sm text-warm mb-6">Define quality standards the AI will follow when generating code.</p>
    <div class="space-y-6">
        <div>
            <label class="block text-sm font-semibold text-navy mb-3">Testing framework</label>
            <p class="text-xs text-warm mb-3">Select the primary testing tool for this application.</p>
            @php $selectedTesting = old('testing_frameworks', ''); @endphp
            <div class="grid grid-cols-4 gap-3">
                <template x-for="tf in testingOptions()" :key="tf.value">
                    <label class="flex flex-col items-center gap-2.5 p-4 border rounded-xl cursor-pointer transition duration-200 text-center"
                           :class="testings === tf.value ? 'border-teal/60 bg-teal/5 shadow-sm' : 'border-cool/40 hover:border-teal/50 hover:shadow-sm'"
                           @click.prevent="testings = testings === tf.value ? '' : tf.value">
                        <input type="radio" name="testing_frameworks" :value="tf.value" class="sr-only"
                               :checked="testings === tf.value">
                        <span class="w-10 h-10 flex items-center justify-center shrink-0 pointer-events-none">
                            <iconify-icon :icon="tf.icon" style="font-size: 36px"
                                          :class="testings === tf.value ? 'text-teal' : 'text-cool'"></iconify-icon>
                        </span>
                        <span class="text-sm font-semibold text-navy" x-text="tf.label"></span>
                    </label>
                </template>
            </div>
            @error('testing_frameworks')
                <p class="text-xs text-coral mt-2 flex items-center gap-1">
                    <iconify-icon icon="heroicons:exclamation-circle" style="font-size: 14px" class="shrink-0"></iconify-icon>
                    {{ $message }}
                </p>
            @enderror
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-navy mb-1">CI / CD</label>
                <select name="ci_cd"
                        class="select select-bordered w-full bg-white text-sm text-navy focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200">
                    <option value="" {{ old('ci_cd') === '' ? 'selected' : '' }}>None — no CI/CD yet</option>
                    @foreach (\App\Support\Label::options('ci_cd') as $val => $label)
                        <option value="{{ $val }}" {{ old('ci_cd') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('ci_cd')
                    <p class="text-xs text-coral mt-1 flex items-center gap-1">
                        <iconify-icon icon="heroicons:exclamation-circle" style="font-size: 14px" class="shrink-0"></iconify-icon>
                        {{ $message }}
                    </p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-navy mb-1">Code style</label>
                <select name="code_style"
                        class="select select-bordered w-full bg-white text-sm text-navy focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200">
                    <option value="" {{ old('code_style') === '' ? 'selected' : '' }}>Auto-detect from technology</option>
                    <template x-for="cs in codeStyleOptions()" :key="cs.value">
                        <option :value="cs.value" x-text="cs.label"
                                :selected="'{{ old('code_style') }}' === cs.value"></option>
                    </template>
                    <option value="custom" {{ old('code_style') === 'custom' ? 'selected' : '' }}>Custom — define later</option>
                </select>
                @error('code_style')
                    <p class="text-xs text-coral mt-1 flex items-center gap-1">
                        <iconify-icon icon="heroicons:exclamation-circle" style="font-size: 14px" class="shrink-0"></iconify-icon>
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </div>
</div>
