@extends('layouts.app')

@section('title', 'New Project')
@section('heading', 'New Project')
@section('subheading', 'Set the foundation — this context will guide your AI agents')

@section('content')

<form action="/projects" method="POST" class="max-w-3xl space-y-8">
    @csrf

    {{-- Identity --}}
    <div class="bg-white rounded-xl border border-cool/50 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-cool/30 bg-slate-50/50">
            <h2 class="text-sm font-semibold text-navy flex items-center gap-2">
                <svg class="w-4 h-4 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Identity
            </h2>
        </div>
        <div class="p-5 space-y-4">
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Project name <span class="text-coral">*</span></label>
                <input name="name" type="text"
                       class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition bg-white"
                       placeholder="e.g. SAID Platform, E-Commerce Backend" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Project path <span class="text-coral">*</span></label>
                <input name="path" type="text"
                       class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition bg-white"
                       placeholder="e.g. /home/user/projects/my-project" required>
                <p class="text-xs text-cool mt-1">Filesystem path where the project files live. The AI agents will read/write from this location.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Short description</label>
                <textarea name="description" rows="2"
                          class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition bg-white"
                          placeholder="What is this project about? This helps the AI understand the domain."></textarea>
            </div>
        </div>
    </div>

    {{-- Business context --}}
    <div class="bg-white rounded-xl border border-cool/50 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-cool/30 bg-slate-50/50">
            <h2 class="text-sm font-semibold text-navy flex items-center gap-2">
                <svg class="w-4 h-4 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Business context
            </h2>
            <p class="text-xs text-warm mt-0.5 ml-6">This information gives AI agents domain awareness when writing specs and code.</p>
        </div>
        <div class="p-5 space-y-4">
            <div>
                <label class="block text-sm font-medium text-navy mb-1">System criticality <span class="text-coral">*</span></label>
                <select name="criticality"
                        class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy bg-white focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition" required>
                    <option value="" disabled selected>Select how critical this system is</option>
                    <option value="mission_critical">Mission Critical — downtime causes severe impact</option>
                    <option value="business_important">Business Important — affects daily operations</option>
                    <option value="administrative">Administrative — supports internal processes</option>
                    <option value="experimental">Experimental — proof of concept or internal tool</option>
                </select>
                <p class="text-xs text-cool mt-1">Helps the AI prioritize reliability, testing depth, and audit strictness.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-navy mb-1">Business sector <span class="text-coral">*</span></label>
                <select name="business_sector"
                        class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy bg-white focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition" required>
                    <option value="" disabled selected>Select the industry or domain</option>
                    <option value="finance">Finance &amp; Banking</option>
                    <option value="healthcare">Healthcare</option>
                    <option value="education">Education</option>
                    <option value="ecommerce">E-commerce &amp; Retail</option>
                    <option value="logistics">Logistics &amp; Transport</option>
                    <option value="government">Government &amp; Public Sector</option>
                    <option value="entertainment">Entertainment &amp; Media</option>
                    <option value="manufacturing">Manufacturing &amp; Industry</option>
                    <option value="other">Other / Not listed</option>
                </select>
                <p class="text-xs text-cool mt-1">Sets domain language, entity naming conventions, and common patterns.</p>
            </div>
        </div>
    </div>

    {{-- Compliance & audience --}}
    <div class="bg-white rounded-xl border border-cool/50 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-cool/30 bg-slate-50/50">
            <h2 class="text-sm font-semibold text-navy flex items-center gap-2">
                <svg class="w-4 h-4 text-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Compliance &amp; audience
            </h2>
        </div>
        <div class="p-5 space-y-5">
            <fieldset>
                <legend class="text-sm font-medium text-navy mb-2">Compliance requirements</legend>
                <p class="text-xs text-warm mb-3">Select all that apply. The AI will enforce relevant standards in generated code.</p>
                <div class="space-y-2">
                    @php
                    $compliance = [
                        'none'  => ['None', 'No regulatory requirements apply'],
                        'gdpr'  => ['GDPR', 'European data protection — governs how personal data is collected, stored, and processed'],
                        'hipaa' => ['HIPAA', 'US healthcare law — protects patient health information and sets security standards'],
                        'pci_dss' => ['PCI-DSS', 'Payment card security — required when handling credit card transactions'],
                        'soc2'  => ['SOC 2', 'Service organization controls — audits security, availability, and confidentiality'],
                        'iso_27001' => ['ISO 27001', 'Global information security standard — certifies your ISMS meets best practices'],
                    ];
                    @endphp
                    @foreach ($compliance as $val => $info)
                    <label class="flex items-start gap-2 px-3 py-2 border border-cool/40 rounded-lg cursor-pointer hover:border-teal/50 transition text-sm text-navy">
                        <input type="checkbox" name="compliance[]" value="{{ $val }}" class="mt-0.5 rounded border-cool text-teal focus:ring-teal shrink-0">
                        <div>
                            <span class="font-medium">{{ $info[0] }}</span>
                            <span class="text-xs text-warm ml-1">— {{ $info[1] }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
            </fieldset>

            <fieldset>
                <legend class="text-sm font-medium text-navy mb-2">Target audience <span class="text-coral">*</span></legend>
                <p class="text-xs text-warm mb-3">Who will use this system? Influences UX tone, auth patterns, and scalability.</p>
                <div class="grid grid-cols-2 gap-2">
                    @php
                    $audiences = [
                        'internal' => ['Internal', 'Employees or staff within your organization'],
                        'b2b'      => ['B2B', 'Other businesses or enterprise clients'],
                        'b2c'      => ['B2C', 'End consumers, general public'],
                        'public'   => ['Public', 'Anyone — no authentication required'],
                    ];
                    @endphp
                    @foreach ($audiences as $val => $info)
                    <label class="flex items-start gap-3 px-4 py-3 border border-cool/40 rounded-lg cursor-pointer hover:border-teal/50 transition">
                        <input type="radio" name="target_audience" value="{{ $val }}" class="mt-0.5 text-teal focus:ring-teal" {{ $val === 'internal' ? 'checked' : '' }}>
                        <div>
                            <span class="text-sm font-medium text-navy">{{ $info[0] }}</span>
                            <p class="text-xs text-warm mt-0.5">{{ $info[1] }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </fieldset>
        </div>
    </div>

    {{-- Submit --}}
    <div class="flex items-center gap-3">
        <button type="submit"
                class="bg-teal text-white rounded-lg px-6 py-2.5 text-sm font-medium hover:bg-teal-dark transition shadow-sm">
            Create project
        </button>
        <a href="/projects" class="text-sm text-warm hover:text-navy transition">Cancel</a>
    </div>
</form>

@endsection
