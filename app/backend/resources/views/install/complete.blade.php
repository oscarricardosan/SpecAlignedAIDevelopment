@extends("install.layout")

@section("step_description")
    Database configured, admin user created — your SAID platform is ready to use.
    Sign in and start building with specifications.
@endsection

@section("content")
<div class="text-center">
    <div class="mb-6">
        <div class="w-20 h-20 rounded-full bg-teal/15 flex items-center justify-center mx-auto mb-4">
            <iconify-icon icon="heroicons:check" class="w-10 h-10 text-teal"></iconify-icon>
        </div>
        <h2 class="text-2xl font-bold text-navy mb-2">You're all set!</h2>
        <p class="text-warm text-sm max-w-sm mx-auto leading-relaxed">
            SAID has been installed successfully. Your database is connected and your administrator account is ready.
        </p>
    </div>

    <div class="bg-slate-50 rounded-lg p-4 mb-6 text-left text-sm">
        <p class="font-medium text-navy mb-2 flex items-center gap-2">
            <iconify-icon icon="heroicons:sparkles" class="w-4 h-4 text-teal"></iconify-icon>
            What's next?
        </p>
        <ul class="space-y-2 text-warm">
            <li class="flex items-start gap-2"><iconify-icon icon="heroicons:chevron-double-right" class="text-teal w-4 h-4 mt-0.5 shrink-0"></iconify-icon> <span><strong class="text-navy">Sign in</strong> with your email and password</span></li>
            <li class="flex items-start gap-2"><iconify-icon icon="heroicons:chevron-double-right" class="text-teal w-4 h-4 mt-0.5 shrink-0"></iconify-icon> <span><strong class="text-navy">Create a project</strong> — the top-level container for your work</span></li>
            <li class="flex items-start gap-2"><iconify-icon icon="heroicons:chevron-double-right" class="text-teal w-4 h-4 mt-0.5 shrink-0"></iconify-icon> <span><strong class="text-navy">Configure AI agents</strong> to generate code from your specs</span></li>
            <li class="flex items-start gap-2"><iconify-icon icon="heroicons:chevron-double-right" class="text-teal w-4 h-4 mt-0.5 shrink-0"></iconify-icon> <span><strong class="text-navy">Write your first spec</strong> and let the AI implement it</span></li>
        </ul>
    </div>

    <a href="{{ route('login') }}"
       class="btn btn-primary gap-2">
        <iconify-icon icon="heroicons:arrow-right-start-on-rectangle" class="w-4 h-4"></iconify-icon>
        Go to sign in
    </a>
</div>
@endsection
