{{-- Delete confirmation modal --}}
{{-- Usage: @include('components.delete-confirmation', ['id' => 'delete-project-123', 'title' => 'Delete Project', 'message' => 'Are you sure you want to delete "Project X"? This action cannot be undone.', 'action' => route('projects.destroy', $project)]) --}}

<dialog id="{{ $id }}" class="modal" @keydown.escape.window="document.getElementById('{{ $id }}')?.close()">
    <div class="modal-box max-w-md">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-full bg-coral/15 flex items-center justify-center shrink-0">
                <iconify-icon icon="heroicons:exclamation-triangle" style="font-size: 24px" class="text-coral"></iconify-icon>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-lg font-bold text-navy mb-1">{{ $title ?? 'Confirm deletion' }}</h3>
                <p class="text-sm text-warm leading-relaxed mb-5">{{ $message ?? 'Are you sure? This action cannot be undone.' }}</p>
                <div class="flex items-center gap-3">
                    <form action="{{ $action ?? '#' }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-error btn-sm gap-2 transition duration-200">
                            <iconify-icon icon="heroicons:trash" style="font-size: 16px"></iconify-icon>
                            {{ $confirmLabel ?? 'Delete' }}
                        </button>
                    </form>
                    <button type="button" onclick="document.getElementById('{{ $id }}')?.close()"
                            class="btn btn-ghost btn-sm transition duration-200">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
