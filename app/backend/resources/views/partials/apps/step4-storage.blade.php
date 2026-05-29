<div x-show="step === 4">
    <h3 class="text-lg font-bold text-navy mb-1">Database &amp; file storage</h3>
    <p class="text-sm text-warm mb-6">How will data and files be persisted?</p>

    {{-- Database engine --}}
    <div class="mb-8">
        <h4 class="text-sm font-semibold text-navy mb-3 flex items-center gap-2">
            <iconify-icon icon="heroicons:circle-stack" style="font-size: 16px" class="text-teal"></iconify-icon>
            Database engine
        </h4>
        <div class="grid grid-cols-4 gap-3">
            @php
            $dbIcons = ['postgresql'=>'devicon:postgresql','mysql'=>'devicon:mysql','sqlite'=>'devicon:sqlite','sqlserver'=>'devicon:microsoftsqlserver','oracle'=>'devicon:oracle','mongodb'=>'devicon:mongodb'];
            $selectedDatabases = old('databases', []);
            @endphp
            @foreach (\App\Support\Label::options('database') as $val => $label)
                @if ($val !== 'none')
                <label class="flex flex-col items-center gap-2.5 p-4 border rounded-xl cursor-pointer transition duration-200 text-center has-[:checked]:border-teal/60 has-[:checked]:bg-teal/5"
                       :class="databases.includes('{{ $val }}') ? 'border-teal/60 bg-teal/5 shadow-sm' : 'border-cool/40 hover:border-teal/50 hover:shadow-sm'">
                    <input type="checkbox" name="databases[]" value="{{ $val }}" class="sr-only"
                           @change="toggleDatabase('{{ $val }}', $el.checked)"
                           {{ in_array($val, $selectedDatabases) ? 'checked' : '' }}>
                    <iconify-icon icon="{{ $dbIcons[$val] ?? 'heroicons:circle-stack' }}" style="font-size: 40px" class="shrink-0 pointer-events-none"
                                  :class="databases.includes('{{ $val }}') ? 'text-teal' : 'text-cool'"></iconify-icon>
                    <span class="text-sm font-semibold text-navy leading-tight">{{ $label }}</span>
                </label>
                @endif
            @endforeach
        </div>
        @error('databases')
            <p class="text-xs text-coral mt-2 flex items-center gap-1">
                <iconify-icon icon="heroicons:exclamation-circle" style="font-size: 14px" class="shrink-0"></iconify-icon>
                {{ $message }}
            </p>
        @enderror
    </div>

    {{-- Data access style (only if DBs selected) --}}
    <div x-show="databases.length > 0" class="mb-8">
        <h4 class="text-sm font-semibold text-navy mb-3 flex items-center gap-2">
            <iconify-icon icon="heroicons:arrows-pointing-in" style="font-size: 16px" class="text-teal"></iconify-icon>
            Data access style <span class="text-coral">*</span>
        </h4>
        <div class="grid grid-cols-2 gap-3">
            @foreach (\App\Support\Label::options('database_access') as $val => $label)
            <label class="flex items-center gap-3 p-4 border rounded-xl cursor-pointer transition duration-200 text-sm text-navy"
                   :class="databaseAccess === '{{ $val }}' ? 'border-teal/60 bg-teal/5 shadow-sm' : 'border-cool/40 hover:border-teal/50 hover:shadow-sm'">
                <input type="radio" name="database_access" value="{{ $val }}" class="sr-only"
                       x-model="databaseAccess"
                       {{ old('database_access') === $val ? 'checked' : '' }}>
                <iconify-icon icon="heroicons:chevron-right" style="font-size: 16px" class="shrink-0 pointer-events-none"
                              :class="databaseAccess === '{{ $val }}' ? 'text-teal' : 'text-cool/40'"></iconify-icon>
                <span>{{ $label }}</span>
            </label>
            @endforeach
        </div>
        @error('database_access')
            <p class="text-xs text-coral mt-2 flex items-center gap-1">
                <iconify-icon icon="heroicons:exclamation-circle" style="font-size: 14px" class="shrink-0"></iconify-icon>
                {{ $message }}
            </p>
        @enderror
    </div>

    {{-- File storage --}}
    <div class="mb-8">
        <h4 class="text-sm font-semibold text-navy mb-3 flex items-center gap-2">
            <iconify-icon icon="heroicons:folder-open" style="font-size: 16px" class="text-teal"></iconify-icon>
            File storage
        </h4>
        <p class="text-xs text-warm mb-3">Select all that apply. Where uploaded files, logs, exports, and static assets should be stored.</p>
        <div class="grid grid-cols-4 gap-3">
            @php
            $storageIcons = [
                'local'      => 'heroicons:computer-desktop',
                's3'         => 'simple-icons:amazons3',
                's3_compat'  => 'heroicons:cloud',
                'ftp'        => 'heroicons:arrow-up-tray',
                'sftp'       => 'heroicons:arrow-up-tray',
                'azure_blob' => 'simple-icons:azuredevops',
                'gcs'        => 'simple-icons:googlecloud',
            ];
            $selectedStorage = old('storage', ['local']);
            @endphp
            @foreach (\App\Support\Label::options('storage') as $val => $label)
                @if ($val !== 'none')
                <label class="flex flex-col items-center gap-2.5 p-4 border rounded-xl cursor-pointer transition duration-200 text-center has-[:checked]:border-teal/60 has-[:checked]:bg-teal/5"
                       :class="storages.includes('{{ $val }}') ? 'border-teal/60 bg-teal/5 shadow-sm' : 'border-cool/40 hover:border-teal/50 hover:shadow-sm'">
                    <input type="checkbox" name="storage[]" value="{{ $val }}" class="sr-only"
                           @change="toggleStorage('{{ $val }}', $el.checked)"
                           {{ in_array($val, $selectedStorage) ? 'checked' : '' }}>
                    <iconify-icon icon="{{ $storageIcons[$val] ?? 'heroicons:folder' }}" style="font-size: 40px" class="shrink-0 pointer-events-none"
                                  :class="storages.includes('{{ $val }}') ? 'text-teal' : 'text-cool'"></iconify-icon>
                    <span class="text-sm font-semibold text-navy leading-tight">{{ $label }}</span>
                </label>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Code repository --}}
    <div>
        <h4 class="text-sm font-semibold text-navy mb-3 flex items-center gap-2">
            <iconify-icon icon="heroicons:code-bracket-square" style="font-size: 16px" class="text-teal"></iconify-icon>
            Code repository
        </h4>
        <p class="text-xs text-warm mb-3">Optional. Select the remote hosting service for your source code.</p>

        <div class="grid grid-cols-5 gap-3 mb-4">
            @php
            $repoIcons = [
                'github'    => 'simple-icons:github',
                'gitlab'    => 'simple-icons:gitlab',
                'gitea'     => 'simple-icons:gitea',
                'bitbucket' => 'simple-icons:bitbucket',
                'other'     => 'heroicons:ellipsis-horizontal-circle',
            ];
            @endphp
            @foreach (\App\Support\Label::options('code_repository') as $val => $label)
                @if ($val !== 'none')
                <label class="flex flex-col items-center gap-2.5 p-3 border rounded-xl cursor-pointer transition duration-200 text-center has-[:checked]:border-teal/60 has-[:checked]:bg-teal/5"
                       :class="codeRepository === '{{ $val }}' ? 'border-teal/60 bg-teal/5 shadow-sm' : 'border-cool/40 hover:border-teal/50 hover:shadow-sm'">
                    <input type="radio" name="code_repository" value="{{ $val }}" class="sr-only"
                           @change="codeRepository = codeRepository === '{{ $val }}' ? '' : '{{ $val }}'"
                           {{ old('code_repository') === $val ? 'checked' : '' }}>
                    <iconify-icon icon="{{ $repoIcons[$val] ?? 'heroicons:server' }}" style="font-size: 28px" class="shrink-0 pointer-events-none"
                                  :class="codeRepository === '{{ $val }}' ? 'text-teal' : 'text-cool'"></iconify-icon>
                    <span class="text-xs font-semibold text-navy leading-tight">{{ $label }}</span>
                </label>
                @endif
            @endforeach
        </div>

        <div x-show="codeRepository" x-cloak>
            <label class="block text-sm font-medium text-navy mb-1">
                Repository URL
                <span class="text-[10px] text-cool/60 font-normal">(optional)</span>
            </label>
            <input name="code_repository_url" type="url" x-model="repoUrl"
                   class="input input-bordered w-full max-w-xl bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200"
                   :placeholder="codeRepository === 'github' ? 'https://github.com/org/repo' : (codeRepository === 'gitlab' ? 'https://gitlab.com/org/repo' : (codeRepository === 'gitea' ? 'https://gitea.example.com/org/repo' : (codeRepository === 'bitbucket' ? 'https://bitbucket.org/org/repo' : 'https://...')))">
            <p class="text-xs text-cool mt-1">The remote URL where this application's source code is hosted.</p>
            @error('code_repository_url')
                <p class="text-xs text-coral mt-1 flex items-center gap-1">
                    <iconify-icon icon="heroicons:exclamation-circle" style="font-size: 14px" class="shrink-0"></iconify-icon>
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>
</div>
