@extends("install.layout")

@section("content")
<form action="/install/database" method="POST" class="space-y-4">
    @csrf

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Host</label>
        <input name="db_host" value="{{ old("db_host", "postgres") }}"
               class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Puerto</label>
        <input name="db_port" value="{{ old("db_port", "5432") }}"
               class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Base de datos</label>
        <input name="db_database" value="{{ old("db_database", "said") }}"
               class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Usuario</label>
        <input name="db_username" value="{{ old("db_username", "said") }}"
               class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Contraseña</label>
        <input name="db_password" type="password" value="{{ old("db_password", "said_secret") }}"
               class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
    </div>

    <input type="hidden" name="db_connection" value="pgsql">

    <button type="submit"
            class="w-full bg-indigo-600 text-white rounded px-4 py-2 text-sm font-medium hover:bg-indigo-700 transition">
        Probar conexión y continuar
    </button>
</form>
@endsection
