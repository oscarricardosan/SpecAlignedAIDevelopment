<div x-show="step === 1">
    <h3 class="text-lg font-bold text-navy mb-1">Choose a platform</h3>
    <p class="text-sm text-warm mb-6">What kind of application are you building?</p>
    <div class="grid grid-cols-3 gap-4 max-w-2xl mx-auto">
        @php
        $platforms = [
            'web'     => ['Browser App', 'Full web app with HTML, CSS, UI.', 'heroicons:globe-alt', null],
            'api'     => ['API Backend', 'Headless — JSON, gRPC, GraphQL. No views.', 'heroicons:server-stack', 'No HTML/CSS rendering.'],
            'mobile'  => ['Mobile App', 'iOS / Android. Pairs with a backend.', 'heroicons:device-phone-mobile', 'No server APIs — consumes a backend.'],
            'desktop' => ['Desktop App', 'Native desktop application.', 'heroicons:computer-desktop', null],
            'cli'     => ['CLI Tool', 'Terminal I/O, no UI.', 'heroicons:command-line', 'No UI, no HTTP server.'],
        ];
        @endphp
        @foreach ($platforms as $val => $info)
        <label class="flex flex-col items-center gap-3 p-5 border rounded-xl cursor-pointer transition duration-200 text-center"
               :class="platform === '{{ $val }}' ? 'border-teal/60 bg-teal/5 shadow-sm' : 'border-cool/40 hover:border-teal/50 hover:shadow-sm'">
            <input type="radio" name="platform" value="{{ $val }}" x-model="platform" class="sr-only" {{ old('platform', 'web') === $val ? 'checked' : '' }}>
            <span class="w-14 h-14 rounded-xl flex items-center justify-center shrink-0 pointer-events-none"
                  :class="platform === '{{ $val }}' ? 'bg-teal/15 text-teal' : 'bg-cool/15 text-cool'">
                <iconify-icon icon="{{ $info[2] }}" style="font-size: 28px"></iconify-icon>
            </span>
            <div>
                <p class="text-sm font-bold text-navy">{{ $info[0] }}</p>
                <p class="text-xs text-warm mt-1 leading-tight">{{ $info[1] }}</p>
                @if ($info[3])
                <p class="text-[11px] text-cool/70 mt-1 italic">{{ $info[3] }}</p>
                @endif
            </div>
        </label>
        @endforeach
    </div>
    @error('platform')
        <p class="text-xs text-coral mt-3 flex items-center gap-1">
            <iconify-icon icon="heroicons:exclamation-circle" style="font-size: 14px" class="shrink-0"></iconify-icon>
            {{ $message }}
        </p>
    @enderror
</div>
