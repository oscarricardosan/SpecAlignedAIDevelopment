<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAID — Instalación</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-md w-full max-w-xl p-8">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-slate-800">🧠 SAID</h1>
            <p class="text-slate-500 text-sm">Instalación del sistema</p>
        </div>

        <div class="flex justify-center gap-2 mb-8">
            @php
                $steps = [
                    ["label" => "Base de datos", "active" => request()->is("install") || request()->is("install/database")],
                    ["label" => "Usuario admin", "active" => request()->is("install/user")],
                    ["label" => "Finalizar", "active" => request()->is("install/complete")],
                ];
            @endphp
            @foreach ($steps as $i => $step)
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full text-xs flex items-center justify-center
                        {{ $step["active"] ? "bg-indigo-600 text-white" : "bg-slate-200 text-slate-500" }}">
                        {{ $i + 1 }}
                    </span>
                    <span class="text-xs {{ $step["active"] ? "text-indigo-600 font-medium" : "text-slate-400" }}">
                        {{ $step["label"] }}
                    </span>
                    @if($i < 2)
                        <span class="text-slate-300">→</span>
                    @endif
                </div>
            @endforeach
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded p-3 mb-4 text-sm">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @yield("content")
    </div>
</body>
</html>
