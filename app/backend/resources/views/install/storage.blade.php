@extends("install.layout")

@section("step_description")
    SAID stores specifications, screens and metadata in S3-compatible storage.
    MinIO is included by default — use the pre-filled values if you're using Docker.
    Also define where your SAID project files will live on disk.
@endsection

@section("content")

<h2 class="text-xl font-bold text-navy mb-2">Storage configuration</h2>
<p class="text-sm text-warm mb-6">
    Your <code class="bg-slate-100 px-1.5 py-0.5 rounded text-navy font-mono text-xs">.spec.md</code> files, screens and metadata are stored in an S3 bucket.
    A local MinIO server is included with Docker — you can keep the defaults or point to an external S3.
</p>

<form action="/install/storage" method="POST" class="space-y-4">
    @csrf

    <div class="bg-slate-50 rounded-lg p-4 mb-2">
        <p class="text-sm font-medium text-navy mb-3">S3 / MinIO credentials</p>
        <div class="space-y-3">
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Endpoint</label>
                <input name="s3_endpoint" value="{{ old("s3_endpoint", "http://minio:9000") }}"
                       class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition" required>
                <p class="text-xs text-cool mt-1">Use <code class="bg-slate-100 px-1 rounded font-mono text-xs">http://minio:9000</code> for the included MinIO container.</p>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Access Key</label>
                    <input name="s3_key" value="{{ old("s3_key", "said_admin") }}"
                           class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Secret Key</label>
                    <input name="s3_secret" type="password" value="{{ old("s3_secret", "said_secret") }}"
                           class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Bucket name</label>
                    <input name="s3_bucket" value="{{ old("s3_bucket", "said-specs") }}"
                           class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Region</label>
                    <input name="s3_region" value="{{ old("s3_region", "us-east-1") }}"
                           class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition">
                    <p class="text-xs text-cool mt-1">Default: us-east-1. Not critical for MinIO.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-slate-50 rounded-lg p-4">
        <p class="text-sm font-medium text-navy mb-3">SAID projects root folder</p>
        <div>
            <label class="block text-sm font-medium text-navy mb-1">Local path</label>
            <input name="said_root" value="{{ old("said_root") }}"
                   placeholder="/choose-your-projects-path"
                   class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition font-mono" required>
            <p class="text-xs text-cool mt-1">
                This folder will be mounted as a Docker volume. All SAID projects and their <code class="bg-slate-100 px-1 rounded font-mono text-xs">.spec.md</code> files live here.
                Make sure this path exists on your host machine.
            </p>
        </div>
    </div>

    <button type="submit"
            class="w-full bg-teal text-white rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-teal-dark transition shadow-sm">
        Save and continue
    </button>
</form>
@endsection
