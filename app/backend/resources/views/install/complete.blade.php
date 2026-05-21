@extends("install.layout")

@section("step_description")
    Database configured, admin user created — your SAID platform is ready to use.
    Sign in and start building with specifications.
@endsection

@section("content")
<div class="text-center">
    <div class="mb-6">
        <div class="w-20 h-20 rounded-full bg-teal/15 flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-navy mb-2">You're all set!</h2>
        <p class="text-warm text-sm max-w-sm mx-auto leading-relaxed">
            SAID has been installed successfully. Your database is connected and your administrator account is ready.
        </p>
    </div>

    <div class="bg-slate-50 rounded-lg p-4 mb-6 text-left text-sm">
        <p class="font-medium text-navy mb-2">What's next?</p>
        <ul class="space-y-2 text-warm">
            <li class="flex items-start gap-2"><span class="text-teal mt-0.5">▸</span> <span><strong class="text-navy">Sign in</strong> with your email and password</span></li>
            <li class="flex items-start gap-2"><span class="text-teal mt-0.5">▸</span> <span><strong class="text-navy">Create a project</strong> — the top-level container for your work</span></li>
            <li class="flex items-start gap-2"><span class="text-teal mt-0.5">▸</span> <span><strong class="text-navy">Configure AI agents</strong> to generate code from your specs</span></li>
            <li class="flex items-start gap-2"><span class="text-teal mt-0.5">▸</span> <span><strong class="text-navy">Write your first spec</strong> and let the AI implement it</span></li>
        </ul>
    </div>

    <a href="/login"
       class="inline-flex items-center gap-2 bg-teal text-white rounded-lg px-6 py-2.5 text-sm font-medium hover:bg-teal-dark transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
        </svg>
        Go to sign in
    </a>
</div>
@endsection
