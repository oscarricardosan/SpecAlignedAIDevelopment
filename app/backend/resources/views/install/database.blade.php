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

<form action="/install/database" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-medium text-navy mb-1">Host</label>
        <input name="db_host" value="{{ old("db_host", "postgres") }}"
               class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition" required>
        <p class="text-xs text-cool mt-1">The hostname where PostgreSQL is running. Use <code class="bg-slate-100 px-1.5 py-0.5 rounded text-navy font-mono text-xs">postgres</code> for Docker.</p>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium text-navy mb-1">Port</label>
            <input name="db_port" value="{{ old("db_port", "5432") }}"
                   class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition" required>
            <p class="text-xs text-cool mt-1">Default: 5432</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-navy mb-1">Database name</label>
            <input name="db_database" value="{{ old("db_database", "said") }}"
                   class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition" required>
            <p class="text-xs text-cool mt-1">Will be created if it doesn't exist.</p>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium text-navy mb-1">Username</label>
            <input name="db_username" value="{{ old("db_username", "said") }}"
                   class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-navy mb-1">Password</label>
            <input name="db_password" type="password" value="{{ old("db_password", "said_secret") }}"
                   class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition">
        </div>
    </div>
    <input type="hidden" name="db_connection" value="pgsql">
    <button type="submit"
            class="w-full bg-teal text-white rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-teal-dark transition shadow-sm">
        Test connection and continue
    </button>
</form>
@endsection
