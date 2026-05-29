@extends('layouts.app')

@section('title', 'Studio')
@section('heading', 'Studio')
@section('subheading', 'Design screens and functionalities for your applications')

@section('content')
<div x-data="studioHome({
    agentsConfigured: {{ Js::from($agentsConfigured) }},
    projects: {{ Js::from($projects) }},
})" x-init="init()">

    {{-- Setup required modal --}}
    <dialog x-ref="setupModal" class="modal">
        <div class="modal-box text-center max-w-sm">
            <iconify-icon icon="heroicons:rocket-launch" style="font-size: 48px" class="text-teal mb-4"></iconify-icon>
            <h3 class="text-lg font-bold text-navy mb-2">Setup Required</h3>
            <p class="text-sm text-warm mb-6 leading-relaxed">
                Before using Studio you need to configure the AI services.
            </p>

            <div class="space-y-3 mb-6">
                <div class="flex items-center gap-3 p-3 rounded-lg border border-cool/30 text-left">
                    <span class="w-7 h-7 rounded-full bg-teal/15 text-teal flex items-center justify-center text-xs font-bold shrink-0">1</span>
                    <div>
                        <p class="text-sm font-semibold text-navy">Add a provider</p>
                        <p class="text-xs text-warm">API credentials for OpenAI, DeepSeek, etc.</p>
                    </div>
                    <a href="{{ route('providers.index') }}"
                       class="shrink-0 text-xs font-medium text-teal hover:text-teal-dark transition duration-200 ml-auto">
                        Go →
                    </a>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-lg border border-cool/30 text-left">
                    <span class="w-7 h-7 rounded-full bg-teal/15 text-teal flex items-center justify-center text-xs font-bold shrink-0">2</span>
                    <div>
                        <p class="text-sm font-semibold text-navy">Configure agents</p>
                        <p class="text-xs text-warm">Assign models by difficulty level.</p>
                    </div>
                    <a href="{{ route('ai-agents.index') }}"
                       class="shrink-0 text-xs font-medium text-teal hover:text-teal-dark transition duration-200 ml-auto">
                        Go →
                    </a>
                </div>
            </div>

            <div class="modal-action justify-center">
                <form method="dialog">
                    <button class="text-sm text-cool hover:text-navy transition duration-200">Close</button>
                </form>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    {{-- Projects --}}
    <template x-if="agentsConfigured">
        <div>
            <h2 class="text-base font-semibold text-navy mb-4">Select a Project</h2>
            <p class="text-sm text-warm mb-6">Choose a project to start designing screens and functionalities.</p>

            <template x-if="projects.length === 0">
                <div class="max-w-2xl mx-auto text-center py-16">
                    <div class="w-16 h-16 rounded-full bg-teal/15 flex items-center justify-center mx-auto mb-4">
                        <iconify-icon icon="heroicons:folder-open" style="font-size: 32px" class="text-teal"></iconify-icon>
                    </div>
                    <p class="text-lg font-bold text-navy mb-2">No projects yet</p>
                    <p class="text-sm text-warm max-w-md mx-auto mb-6">Create a project first to start designing screens and functionalities.</p>
                    <a href="{{ route('projects.create') }}"
                       class="bg-teal text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-teal-dark transition duration-200 inline-block">
                        Create your first project
                    </a>
                </div>
            </template>

            <template x-if="projects.length > 0">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <template x-for="project in projects" :key="project.id">
                        <div class="card bg-base-100 border border-cool/50 hover:border-teal/60 hover:shadow-sm transition duration-200 cursor-pointer p-5"
                             @click="window.location.href = '{{ route('projects.index') }}/' + project.id">
                            <div class="flex items-center gap-3 mb-2">
                                <iconify-icon icon="heroicons:folder" style="font-size: 24px" class="text-teal shrink-0"></iconify-icon>
                                <span class="text-sm font-semibold text-navy truncate" x-text="project.name"></span>
                            </div>
                            <p class="text-xs text-warm line-clamp-2" x-text="project.description || 'No description'"></p>
                            <div class="flex items-center gap-2 mt-3">
                                <span class="badge badge-xs bg-cool/10 border-cool/30 text-cool text-xs"
                                      x-text="project.criticality?.replace(/_/g, ' ') || ''"></span>
                                <span class="badge badge-xs bg-cool/10 border-cool/30 text-cool text-xs"
                                      x-text="project.business_sector?.replace(/_/g, ' ') || ''"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </template>
</div>
@endsection

@push('scripts')
<script>
function studioHome({ agentsConfigured, projects }) {
    return {
        agentsConfigured,
        projects,
        init() {
            if (!this.agentsConfigured) {
                this.$refs.setupModal.showModal();
            }
        },
    };
}
</script>
@endpush
