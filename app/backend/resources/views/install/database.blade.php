@extends("install.layout")

@section("step_description")
    SAID needs a PostgreSQL database to store projects, specifications, users, and test results.
    These are the default values for the Docker environment.
@endsection

@section("content")
<h2 class="text-xl font-bold text-navy mb-2">Database configuration</h2>
<p class="text-sm text-warm mb-6">
    Enter the connection details for your PostgreSQL server. The default values match the Docker setup — you can leave them as they are if you're using Docker Compose.
</p>

<form action="{{ route('install.database.save') }}" method="POST" class="space-y-4"
      x-data="{ submitting: false }" @submit="submitting = true">
    @csrf
    <div>
        <label class="block text-sm font-medium text-navy mb-1">Host</label>
        <input name="db_host" value="{{ old('db_host', 'postgres') }}"
               class="input input-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition" required>
        <p class="text-xs text-cool mt-1">The hostname where PostgreSQL is running. Use <code class="bg-slate-100 px-1.5 py-0.5 rounded text-navy font-mono text-xs">postgres</code> for Docker.</p>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium text-navy mb-1">Port</label>
            <input name="db_port" value="{{ old('db_port', '5432') }}"
                   class="input input-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition" required>
            <p class="text-xs text-cool mt-1">Default: 5432</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-navy mb-1">Database name</label>
            <input name="db_database" value="{{ old('db_database', 'said') }}"
                   class="input input-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition" required>
            <p class="text-xs text-cool mt-1">Will be created if it doesn't exist.</p>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium text-navy mb-1">Username</label>
            <input name="db_username" value="{{ old('db_username', 'said') }}"
                   class="input input-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-navy mb-1">Password</label>
            <input name="db_password" type="password" value="{{ old('db_password', 'said_secret') }}"
                   class="input input-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition">
        </div>
    </div>
    <input type="hidden" name="db_connection" value="pgsql">
    <button type="submit"
            :disabled="submitting"
            class="btn btn-primary w-full disabled:opacity-60 disabled:cursor-not-allowed">
        <span x-show="!submitting">Test connection and continue</span>
        <span x-show="submitting" class="flex items-center gap-2">
            <iconify-icon icon="svg-spinners:180-ring" style="font-size: 16px"></iconify-icon>
            Testing&hellip;
        </span>
    </button>
</form>
@endsection
