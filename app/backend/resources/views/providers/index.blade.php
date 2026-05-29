@extends('layouts.app')

@section('title', 'Providers')
@section('heading', 'Providers')
@section('subheading', 'Manage AI provider credentials — reusable across all agents')

@section('content')
<div x-data="providersManager({{ Js::from($providers) }})">

    <div class="flex items-center justify-between mb-5">
        <p class="text-sm text-warm" x-text="providers.length + ' provider(s) configured'"></p>
        <button type="button" @click="showForm = !showForm"
                class="bg-teal text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-dark transition duration-200">
            <span x-show="!showForm">Add Provider</span>
            <span x-show="showForm">Cancel</span>
        </button>
    </div>

    {{-- Add form --}}
    <div x-show="showForm" x-transition class="card bg-base-100 border border-cool/50 p-5 mb-6"
         x-data="{ submitting: false }">
        <form @submit.prevent="addProvider($refs.form, $data)" x-ref="form"
              class="grid grid-cols-2 gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Name <span class="text-coral">*</span></label>
                <input type="text" name="name" required
                       class="input input-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200"
                       placeholder="e.g. My DeepSeek account">
            </div>
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Provider <span class="text-coral">*</span></label>
                <select name="provider_code" required
                        class="select select-bordered w-full bg-white text-sm text-navy focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200">
                    <option value="">Select…</option>
                    <option value="openai">OpenAI</option>
                    <option value="anthropic">Anthropic (Claude)</option>
                    <option value="deepseek">DeepSeek</option>
                    <option value="groq">Groq</option>
                    <option value="together">Together AI</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-navy mb-1">API Key <span class="text-coral">*</span></label>
                <input type="password" name="api_key" required
                       class="input input-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200">
            </div>
            <div>
                <label class="block text-sm font-medium text-navy mb-1">API Endpoint</label>
                <input type="url" name="api_endpoint"
                       class="input input-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200"
                       placeholder="Leave empty for default">
            </div>
            <div class="col-span-2 flex justify-end">
                <button type="submit" :disabled="submitting"
                        class="bg-teal text-white rounded-lg px-6 py-2.5 text-sm font-medium hover:bg-teal-dark transition shadow-sm disabled:opacity-60 disabled:cursor-not-allowed">
                    <span x-show="!submitting">Save Provider</span>
                    <span x-show="submitting" class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Saving…
                    </span>
                </button>
            </div>
        </form>
    </div>

    {{-- Empty state --}}
    <template x-if="providers.length === 0">
        <div class="max-w-2xl mx-auto text-center py-16">
            <div class="w-16 h-16 rounded-full bg-teal/15 flex items-center justify-center mx-auto mb-4">
                <iconify-icon icon="heroicons:cloud" style="font-size: 32px" class="text-teal"></iconify-icon>
            </div>
            <p class="text-lg font-bold text-navy mb-2">No providers yet</p>
            <p class="text-sm text-warm max-w-md mx-auto">Add your first AI provider. Credentials are encrypted and reusable across all agents.</p>
        </div>
    </template>

    {{-- Providers table --}}
    <template x-if="providers.length > 0">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr class="border-b border-cool/30">
                        <th class="text-xs font-semibold text-warm uppercase tracking-wider py-3">Name</th>
                        <th class="text-xs font-semibold text-warm uppercase tracking-wider py-3">Provider</th>
                        <th class="text-xs font-semibold text-warm uppercase tracking-wider py-3">Endpoint</th>
                        <th class="text-xs font-semibold text-warm uppercase tracking-wider py-3 w-16"></th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="p in providers" :key="p.id">
                        <tr class="border-b border-cool/20">
                            <td class="py-3 text-sm font-medium text-navy" x-text="p.name"></td>
                            <td class="py-3">
                                <span class="badge badge-sm bg-cool/10 border-cool/30 text-cool text-xs"
                                      x-text="humanize(p.provider_code)"></span>
                            </td>
                            <td class="py-3 text-xs text-cool" x-text="p.api_endpoint || 'Default'"></td>
                            <td class="py-3 text-right">
                                <button type="button" @click="deleteProvider(p.id)"
                                        class="text-cool/40 hover:text-coral transition duration-200"
                                        :title="'Delete ' + p.name">
                                    <iconify-icon icon="heroicons:trash" style="font-size: 16px"></iconify-icon>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </template>

    {{-- Toast --}}
    <div x-show="toast.show" x-transition class="fixed bottom-6 right-6 z-[100] pointer-events-auto" @click="toast.show = false">
        <div role="alert" class="alert shadow-lg" :class="toast.type === 'success' ? 'alert-success' : 'alert-error'">
            <iconify-icon :icon="toast.type === 'success' ? 'heroicons:check-circle' : 'heroicons:exclamation-circle'"
                          style="font-size: 20px" class="shrink-0"></iconify-icon>
            <span x-text="toast.text"></span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function providersManager(initialProviders) {
    return {
        showForm: false,
        providers: initialProviders,
        toast: { show: false, type: 'success', text: '' },

        humanize(code) {
            const map = { openai: 'OpenAI', anthropic: 'Anthropic', deepseek: 'DeepSeek', groq: 'Groq', together: 'Together AI' };
            return map[code] || code;
        },

        showToast(type, text) {
            this.toast = { show: true, type, text };
            setTimeout(() => this.toast.show = false, 3500);
        },

        async addProvider(formEl, alpineData) {
            alpineData.submitting = true;
            const fd = new FormData(formEl);
            fd.append('_token', '{{ csrf_token() }}');
            try {
                const res = await fetch('{{ route('providers.store') }}', {
                    method: 'POST', body: fd, headers: { 'Accept': 'application/json' },
                });
                const data = await res.json();
                if (data.ok) {
                    this.providers.push(data.provider);
                    formEl.reset();
                    this.showForm = false;
                    this.showToast('success', 'Provider added.');
                } else {
                    this.showToast('error', data.message || 'Failed.');
                }
            } catch (e) {
                this.showToast('error', 'Network error.');
            }
            alpineData.submitting = false;
        },

        async deleteProvider(id) {
            if (!confirm('Delete this provider? Agents using it will lose their configuration.')) return;
            try {
                const url = '{{ route('providers.destroy', ['provider' => '__ID__']) }}'.replace('__ID__', id);
                const res = await fetch(url, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                });
                const data = await res.json();
                if (data.ok) {
                    this.providers = this.providers.filter(p => p.id !== id);
                    this.showToast('success', 'Provider removed.');
                }
            } catch (e) {
                this.showToast('error', 'Network error.');
            }
        },
    };
}
</script>
@endpush
