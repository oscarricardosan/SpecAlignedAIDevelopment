@extends('layouts.app')

@section('title', 'Agents')
@section('heading', 'Agents')
@section('subheading', 'Assign provider and model per difficulty level')

@section('content')
<div x-data="agentsManager({
    initialAgents: {{ Js::from($agents) }},
    initialProviders: {{ Js::from($providers) }},
})">

    {{-- No providers — guide user --}}
    <template x-if="providers.length === 0">
        <div class="max-w-lg mx-auto text-center py-16">
            <div class="w-16 h-16 rounded-full bg-teal/15 flex items-center justify-center mx-auto mb-4">
                <iconify-icon icon="heroicons:cpu-chip" style="font-size: 32px" class="text-teal"></iconify-icon>
            </div>
            <p class="text-lg font-bold text-navy mb-2">No providers configured</p>
            <p class="text-sm text-warm max-w-md mx-auto mb-6">You need at least one provider before configuring agents.</p>
            <a href="{{ route('providers.index') }}"
               class="bg-teal text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-teal-dark transition duration-200 inline-block">
                Configure providers first
            </a>
        </div>
    </template>

    {{-- Has providers — show agents grid --}}
    <template x-if="providers.length > 0">
        <div>
            <div class="flex items-center justify-between mb-5">
                <p class="text-sm text-warm">All three levels must be configured on every agent before Studio is available.</p>
                <a href="{{ route('providers.index') }}"
                   class="text-sm font-medium text-teal hover:text-teal-dark transition duration-200 flex items-center gap-1.5">
                    <iconify-icon icon="heroicons:cloud" style="font-size: 14px"></iconify-icon>
                    <span x-text="providers.length + ' provider(s)'"></span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <template x-for="agent in agents" :key="agent.id">
                    <div class="card bg-base-100 border border-cool/50 hover:border-teal/60 hover:shadow-sm transition duration-200"
                         :class="{ 'border-teal/60 bg-teal/5': agent._saved }"
                         x-effect="if (agent._saved) { setTimeout(() => agent._saved = false, 1200) }">

                        {{-- Avatar + name --}}
                        <div class="p-4 text-center">
                            <img :src="agentAvatar(agent)" :alt="agent.name"
                                 class="w-12 h-12 rounded-xl bg-cool/10 mx-auto mb-2">
                            <p class="text-sm font-semibold text-navy" x-text="agent.name"></p>
                            <input type="text" x-model="agent.name"
                                   class="input input-bordered input-xs w-full mt-2 bg-white text-xs text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition duration-200 text-center"
                                   placeholder="Alias…">
                        </div>

                        <div class="border-t border-cool/20"></div>

                        {{-- Difficulty levels --}}
                        <div class="p-3 space-y-2.5">
                            <div>
                                <label class="block text-[11px] font-semibold text-warm uppercase tracking-wider mb-1">Low</label>
                                <select x-model="agent.provider_id_low"
                                        class="select select-bordered select-xs w-full bg-white text-xs text-navy focus:outline-none focus:border-teal transition duration-200 mb-1">
                                    <option value="">—</option>
                                    <template x-for="p in providers" :key="p.id">
                                        <option :value="p.id" x-text="p.name"></option>
                                    </template>
                                </select>
                                <input type="text" x-model="agent.model_low"
                                       class="input input-bordered input-xs w-full bg-white text-xs text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal transition duration-200"
                                       placeholder="Model">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-warm uppercase tracking-wider mb-1">Medium</label>
                                <select x-model="agent.provider_id_medium"
                                        class="select select-bordered select-xs w-full bg-white text-xs text-navy focus:outline-none focus:border-teal transition duration-200 mb-1">
                                    <option value="">—</option>
                                    <template x-for="p in providers" :key="p.id">
                                        <option :value="p.id" x-text="p.name"></option>
                                    </template>
                                </select>
                                <input type="text" x-model="agent.model_medium"
                                       class="input input-bordered input-xs w-full bg-white text-xs text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal transition duration-200"
                                       placeholder="Model">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-warm uppercase tracking-wider mb-1">High</label>
                                <select x-model="agent.provider_id_high"
                                        class="select select-bordered select-xs w-full bg-white text-xs text-navy focus:outline-none focus:border-teal transition duration-200 mb-1">
                                    <option value="">—</option>
                                    <template x-for="p in providers" :key="p.id">
                                        <option :value="p.id" x-text="p.name"></option>
                                    </template>
                                </select>
                                <input type="text" x-model="agent.model_high"
                                       class="input input-bordered input-xs w-full bg-white text-xs text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal transition duration-200"
                                       placeholder="Model">
                            </div>
                        </div>

                        {{-- Save --}}
                        <div class="px-3 pb-3">
                            <button type="button" @click="saveAgent(agent)"
                                    :disabled="agent._saving"
                                    class="bg-teal text-white rounded-lg px-3 py-1.5 text-xs font-medium hover:bg-teal-dark transition w-full disabled:opacity-60 disabled:cursor-not-allowed">
                                <span x-show="!agent._saving">Save</span>
                                <span x-show="agent._saving" class="flex items-center justify-center gap-1.5">
                                    <svg class="animate-spin w-3 h-3" viewBox="0 0 24 24" fill="none">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
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
function agentsManager({ initialAgents, initialProviders }) {
    return {
        providers: initialProviders,
        agents: initialAgents.map(a => ({ ...a, _saving: false, _saved: false })),
        toast: { show: false, type: 'success', text: '' },

        agentAvatar(agent) {
            const seed = agent.name || agent.type;
            return `https://api.dicebear.com/9.x/bottts-neutral/svg?seed=${encodeURIComponent(seed)}&size=48`;
        },

        showToast(type, text) {
            this.toast = { show: true, type, text };
            setTimeout(() => this.toast.show = false, 3500);
        },

        async saveAgent(agent) {
            agent._saving = true;
            const fd = new FormData();
            fd.append('_method', 'PATCH');
            fd.append('_token', '{{ csrf_token() }}');
            if (agent.name) fd.append('name', agent.name);
            if (agent.provider_id_low) fd.append('provider_id_low', agent.provider_id_low);
            if (agent.model_low) fd.append('model_low', agent.model_low);
            if (agent.provider_id_medium) fd.append('provider_id_medium', agent.provider_id_medium);
            if (agent.model_medium) fd.append('model_medium', agent.model_medium);
            if (agent.provider_id_high) fd.append('provider_id_high', agent.provider_id_high);
            if (agent.model_high) fd.append('model_high', agent.model_high);

            try {
                const url = '{{ route('ai-agents.update', ['agent' => '__ID__']) }}'.replace('__ID__', agent.id);
                const res = await fetch(url, { method: 'POST', body: fd, headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                if (data.ok) {
                    agent._saved = true;
                    this.showToast('success', 'Agent saved.');
                } else {
                    this.showToast('error', data.message || 'Failed.');
                }
            } catch (e) {
                this.showToast('error', 'Network error.');
            }
            agent._saving = false;
        },
    };
}
</script>
@endpush
