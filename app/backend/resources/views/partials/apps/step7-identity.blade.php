{{-- Step 7: Identity & Context --}}
<div x-show="step === 7">
    <h3 class="text-lg font-bold text-navy mb-1">Application identity &amp; context</h3>
    <p class="text-sm text-warm mb-6">Name your app, choose its folder, and provide extra context for AI agents.</p>

    <div class="space-y-5">
        {{-- Name --}}
        <div>
            <label class="block text-sm font-medium text-navy mb-1">Application name <span class="text-coral">*</span></label>
            <input name="name" type="text" x-model="appName"
                   class="input input-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200"
                   placeholder="e.g. Web Frontend, Admin API, Mobile App">
            @error('name')
                <p class="text-xs text-coral mt-1 flex items-center gap-1">
                    <iconify-icon icon="heroicons:exclamation-circle" style="font-size: 14px" class="shrink-0"></iconify-icon>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-sm font-medium text-navy mb-1">Short description</label>
            <textarea name="description" rows="2" x-model="appDescription"
                      class="textarea textarea-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200"
                      placeholder="What does this app do? Helps the AI understand its purpose."></textarea>
        </div>

        {{-- Folder --}}
        <div>
            <label class="block text-sm font-medium text-navy mb-1">Application folder <span class="text-coral">*</span></label>
            <div class="relative">
                <input name="path" type="text" x-model="appPath" required
                       @click="openBrowser = true; $nextTick(() => $refs.appFolderModal?.showModal())"
                       class="input input-bordered w-full pl-10 bg-white text-sm text-navy placeholder:text-cool/60 cursor-pointer focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200"
                       placeholder="Click to browse — must be a subfolder of the project">
                <iconify-icon icon="heroicons:folder" style="font-size: 16px" class="absolute left-3 top-1/2 -translate-y-1/2 text-cool"></iconify-icon>
            </div>
            <p class="text-xs text-cool mt-1">The app must live inside the project folder. Cannot be the project root or empty.</p>
            @error('path')
                <p class="text-xs text-coral mt-1 flex items-center gap-1">
                    <iconify-icon icon="heroicons:exclamation-circle" style="font-size: 14px" class="shrink-0"></iconify-icon>
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Context tabs --}}
        <div class="border border-cool/30 rounded-xl overflow-hidden">
            <div class="flex border-b border-cool/20 bg-cool/10">
                <button type="button" @click="contextTab = 'architecture'"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold transition duration-200 border-b-2"
                        :class="contextTab === 'architecture' ? 'text-teal border-teal bg-white' : 'text-cool border-transparent hover:text-navy'">
                    <iconify-icon icon="heroicons:square-3-stack-3d" style="font-size: 16px" class="inline-block mr-1.5 align-text-bottom"></iconify-icon>
                    Architecture
                </button>
                <button type="button" @click="contextTab = 'stack'"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold transition duration-200 border-b-2"
                        :class="contextTab === 'stack' ? 'text-teal border-teal bg-white' : 'text-cool border-transparent hover:text-navy'">
                    <iconify-icon icon="heroicons:cube" style="font-size: 16px" class="inline-block mr-1.5 align-text-bottom"></iconify-icon>
                    Stack
                </button>
                <button type="button" @click="contextTab = 'guidelines'"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold transition duration-200 border-b-2"
                        :class="contextTab === 'guidelines' ? 'text-teal border-teal bg-white' : 'text-cool border-transparent hover:text-navy'">
                    <iconify-icon icon="heroicons:clipboard-document-list" style="font-size: 16px" class="inline-block mr-1.5 align-text-bottom"></iconify-icon>
                    Conventions
                </button>
            </div>
            <div class="p-4">
                <div x-show="contextTab === 'architecture'">
                    <label class="block text-xs font-semibold text-navy mb-1">Architecture decisions</label>
                    <p class="text-xs text-warm mb-3">Document key architectural choices and patterns.</p>
                    <textarea name="context_architecture" rows="5" x-model="contextArchitecture"
                              class="textarea textarea-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200"
                              placeholder="e.g.&#10;- CQRS for write-heavy endpoints&#10;- Repository pattern for all DB access&#10;- Event sourcing for audit log&#10;- API versioning via URL prefix (/v1/...)"></textarea>
                </div>
                <div x-show="contextTab === 'stack'">
                    <label class="block text-xs font-semibold text-navy mb-1">Stack &amp; dependencies</label>
                    <p class="text-xs text-warm mb-3">Describe the tech stack, libraries, and tooling decisions.</p>
                    <textarea name="context_stack" rows="5" x-model="contextStack"
                              class="textarea textarea-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200"
                              placeholder="e.g.&#10;- Authentication: JWT + OAuth2&#10;- Queue: Redis / Horizon&#10;- HTTP client: Guzzle&#10;- Front-end assets: Vite + Tailwind"></textarea>
                </div>
                <div x-show="contextTab === 'guidelines'">
                    <label class="block text-xs font-semibold text-navy mb-1">
                        Conventions &amp; standards
                    </label>
                    <p class="text-xs text-warm mb-3">
                        <span x-show="platform !== 'api'">Naming conventions, code style, component patterns, design tokens.</span>
                        <span x-show="platform === 'api'">Naming conventions, response formats, error handling, status codes.</span>
                    </p>
                    <textarea name="context_guidelines" rows="5" x-model="contextGuidelines"
                              class="textarea textarea-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200"
                              :placeholder="platform === 'api'
                                  ? 'e.g.\n- RESTful with JSON:API spec\n- Pagination: cursor-based\n- Rate limiting: 100 req/min\n- Error format: { error: string, code: number }'
                                  : 'e.g.\n- Naming: camelCase for JS, snake_case for PHP\n- Primary: #1a365d (navy)\n- Accent: #38b2ac (teal)\n- Font: Inter, system sans-serif\n- Mobile-first: 375 → 1440px'"></textarea>
                </div>
            </div>
        </div>

        {{-- General notes --}}
        <div>
            <label class="block text-sm font-semibold text-navy mb-2 flex items-center gap-2">
                <iconify-icon icon="heroicons:pencil-square" style="font-size: 16px" class="text-teal"></iconify-icon>
                Additional notes
                <span class="text-[10px] text-cool/60 font-normal">(optional)</span>
            </label>
            <textarea name="notes" rows="3"
                      class="textarea textarea-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200"
                      placeholder="Team conventions, naming rules, dependency constraints...">{{ old('notes') }}</textarea>
        </div>
    </div>
</div>

{{-- Folder browser modal for app path --}}
<dialog x-ref="appFolderModal" class="modal" @click.self="openBrowser = false; $refs.appFolderModal?.close()" @close="openBrowser = false">
    <div class="modal-box max-w-lg p-0 flex flex-col max-h-[80vh] overflow-hidden" @click.stop @keydown.enter.prevent>
        <div class="flex items-center justify-between px-5 py-3 border-b border-cool/30 bg-cool/10 shrink-0 rounded-t-2xl">
            <h3 class="text-sm font-semibold text-navy flex items-center gap-2">
                <iconify-icon icon="heroicons:folder-open" style="font-size: 16px" class="text-teal"></iconify-icon>
                Select app folder
            </h3>
            <button type="button" @click="openBrowser = false; $refs.appFolderModal?.close()" class="btn btn-ghost btn-xs btn-circle transition duration-200">
                <iconify-icon icon="heroicons:x-mark" style="font-size: 16px"></iconify-icon>
            </button>
        </div>

        <nav class="flex items-center gap-1 px-5 py-2 text-sm text-warm flex-wrap border-b border-cool/20 shrink-0" aria-label="Breadcrumb">
            <template x-for="(crumb, idx) in appBreadcrumbs" :key="crumb.path">
                <span class="flex items-center gap-1">
                    <span x-show="idx > 1" class="text-cool/50">/</span>
                    <button type="button" @click="appNavigate(crumb.path)"
                            x-show="idx > 0"
                            :class="idx === appBreadcrumbs.length - 1 ? 'text-navy font-semibold cursor-default' : 'text-teal hover:underline cursor-pointer'"
                            class="transition duration-200"
                            x-text="crumb.label"></button>
                    <span x-show="idx === 0" class="text-navy font-semibold" x-text="crumb.label"></span>
                </span>
            </template>
        </nav>

        <div class="flex-1 overflow-y-auto p-3 min-h-0">
            <div x-show="appLoading" class="space-y-1 py-1">
                <template x-for="n in 5" :key="n">
                    <div class="flex items-center gap-2.5 px-3 py-2">
                        <div class="w-4 h-4 rounded bg-cool/20 animate-pulse shrink-0"></div>
                        <div class="h-3.5 rounded bg-cool/15 animate-pulse flex-1"></div>
                    </div>
                </template>
            </div>
            <div x-show="appError" x-cloak class="text-sm text-coral py-4 text-center" x-text="appError"></div>
            <div x-show="!appLoading && !appError" class="space-y-0.5">
                <template x-if="appItems.length === 0 && appBreadcrumbs.length > 2">
                    <p class="text-sm text-cool p-4 text-center italic">This folder is empty. Create a subfolder to continue.</p>
                </template>
                <template x-if="appItems.length === 0 && appBreadcrumbs.length <= 2">
                    <p class="text-sm text-cool p-4 text-center italic">Navigate deeper into a subfolder or create one to place your application.</p>
                </template>
                <template x-for="item in appItems" :key="item.path">
                    <button type="button" @click="appNavigate(item.path)"
                            class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-navy hover:bg-teal/10 transition duration-200 text-left">
                        <iconify-icon icon="heroicons:folder" style="font-size: 16px" class="text-peach shrink-0"></iconify-icon>
                        <span x-text="item.name"></span>
                    </button>
                </template>
            </div>
        </div>

        <div class="border-t border-cool/20 px-4 py-3 flex items-center gap-3 shrink-0 bg-cool/10">
            <div class="flex items-center gap-2 flex-1">
                <button type="button" @click="appCreating = !appCreating; if(appCreating) $nextTick(() => $refs.appNewFolderInput?.focus())"
                        class="btn btn-ghost btn-xs text-teal transition duration-200"
                        x-text="appCreating ? 'Cancel' : '+ New folder'"></button>
                <template x-if="appCreating">
                    <div class="flex items-center gap-1.5 flex-1">
                        <input type="text" x-model="appNewFolderName" x-ref="appNewFolderInput"
                               @keydown.enter.prevent="appCreateFolder()"
                               placeholder="Folder name"
                               class="input input-bordered input-xs flex-1 bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal transition duration-200">
                        <button type="button" @click="appCreateFolder()"
                                class="btn btn-primary btn-xs transition duration-200">
                            Create
                        </button>
                    </div>
                </template>
            </div>
            <template x-if="appBreadcrumbs.length > 2">
                <button type="button" @click="appSelectCurrent(); openBrowser = false; $refs.appFolderModal?.close()"
                        class="btn btn-neutral btn-sm gap-1 transition duration-200">
                    <iconify-icon icon="heroicons:check" style="font-size: 16px"></iconify-icon>
                    Select this folder
                </button>
            </template>
            <template x-if="appBreadcrumbs.length <= 2">
                <span class="text-sm text-warm">Navigate into a subfolder to select it</span>
            </template>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button @click="openBrowser = false">close</button>
    </form>
</dialog>
