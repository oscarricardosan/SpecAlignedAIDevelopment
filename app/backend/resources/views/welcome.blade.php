<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAID — Welcome</title>
    <link rel="icon" type="image/png" href="/assets/favicon.png">
    <script src="https://cdn.tailwindcss.com?v=2"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@2/dist/iconify-icon.min.js"></script>
    <link rel="stylesheet" href="/css/said.css?v=9">
</head>
<body class="min-h-screen flex items-center justify-center p-6">

    <div class="max-w-lg w-full text-center">
        <img src="/assets/favicon.png" alt="SAID" class="w-20 h-20 mx-auto mb-5">
        <h1 class="text-3xl font-bold text-navy mb-2 tracking-tight">SAID</h1>
        <p class="text-warm text-sm mb-8">Spec-Aligned AI Development</p>

        <div class="bg-white rounded-xl border border-cool/40 p-6 mb-6 text-left shadow-sm">
            <p class="text-navy text-sm leading-relaxed mb-4">
                <strong>SAID</strong> is a methodology that keeps you and your AI agents
                perfectly aligned through <strong>specifications</strong> — structured documents that describe
                exactly what each feature should do.
            </p>
            <ul class="space-y-2 text-sm text-warm">
                <li class="flex items-start gap-2"><iconify-icon icon="heroicons:chevron-double-right" class="text-teal w-4 h-4 mt-0.5 shrink-0"></iconify-icon> Write specs in plain, structured language — no coding required</li>
                <li class="flex items-start gap-2"><iconify-icon icon="heroicons:chevron-double-right" class="text-teal w-4 h-4 mt-0.5 shrink-0"></iconify-icon> AI agents read specs and generate the code for you</li>
                <li class="flex items-start gap-2"><iconify-icon icon="heroicons:chevron-double-right" class="text-teal w-4 h-4 mt-0.5 shrink-0"></iconify-icon> A second AI audits the code to make sure it follows the spec</li>
                <li class="flex items-start gap-2"><iconify-icon icon="heroicons:chevron-double-right" class="text-teal w-4 h-4 mt-0.5 shrink-0"></iconify-icon> Never lose context between sessions — the spec is your shared memory</li>
            </ul>
        </div>

        <a href="{{ route('install.database') }}"
           class="btn btn-primary gap-2">
            <iconify-icon icon="heroicons:heart" class="w-5 h-5"></iconify-icon>
            Start Installation
        </a>

        <p class="text-xs text-cool mt-6">
            You will configure the database and create the first admin user in just two steps.
        </p>
    </div>

</body>
</html>
