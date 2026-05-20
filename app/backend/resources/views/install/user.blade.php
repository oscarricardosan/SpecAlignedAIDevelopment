@extends("install.layout")

@section("content")
<form action="/install/user" method="POST" class="space-y-4">
    @csrf

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Nombre</label>
        <input name="name" value="{{ old("name") }}"
               class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Correo electrónico</label>
        <input name="email" type="email" value="{{ old("email") }}"
               class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Contraseña</label>
        <input name="password" type="password"
               class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required minlength="8">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Confirmar contraseña</label>
        <input name="password_confirmation" type="password"
               class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required minlength="8">
    </div>

    <button type="submit"
            class="w-full bg-indigo-600 text-white rounded px-4 py-2 text-sm font-medium hover:bg-indigo-700 transition">
        Crear usuario y finalizar instalación
    </button>
</form>
@endsection
