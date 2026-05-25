# 🧠 SAID Architecture

> *Arquitectura del sistema de la plataforma SAID*

---

## Visión General

SAID se ejecuta en **Docker**, tanto en local como en servidor. Todo el sistema se comunica vía **APIs REST**, consumidas por el frontend, las IAs y otros usuarios de la plataforma.

La base de datos (PostgreSQL) reside en el servidor y es compartida por todos los usuarios que trabajan en un mismo proyecto. Los archivos de cada proyecto residen en el sistema de archivos **local de cada usuario** (o en una carpeta compartida/Git), sin conflictos con la BD centralizada.

```
🐳 Docker
┌──────────────────────────────────────────────────────────────────────────┐
│  Nginx                                                                    │
│  ┌──────────────────┐  ┌────────────────────────────────────────────┐    │
│  │ Frontend           │  │ API REST (JSON)                            │    │
│  │ Alpine.js + Tail.  │  │ /api/proyectos                             │    │
│  │ Componentes reutil.│  │ /api/apps                                  │    │
│  │                    │  │ /api/modulos                               │    │
│  │                    │  │ /api/funcionalidades                       │    │
│  │                    │  │ /api/agentes                               │    │
│  │                    │  │ /api/tests                                 │    │
│  │                    │  │ /api/chat                                  │    │
│  │                    │  │ /api/git                                   │    │
│  └──────────────────┘  └──────────┬─────────────────────────────────┘    │
│                                    │                                       │
│                           ┌────────▼────────┐                             │
│                           │  Laravel Backend  │                             │
│                           │  ┌────────────┐   │                             │
│                           │  │ Auth        │   │  ← Usuarios, roles        │
│                           │  ├────────────┤   │                             │
│                           │  │ Controlador │   │                             │
│                           │  ├────────────┤   │                             │
│                           │  │ Servicio    │   │                             │
│                           │  ├────────────┤   │                             │
│                           │  │ Repositorio │   │                             │
│                           │  ├────────────┤   │                             │
│                           │  │ Adaptadores  │   │                             │
│                           │  │ (IA, Git,   │   │                             │
│                           │  │  Filesystem)│   │                             │
│                           │  └────────────┘   │                             │
│                           └───────────────────┘                             │
│                                    │                                        │
│  ┌───────────────┬─────────────────┼────────────────┬──────────────────┐   │
│  │ PostgreSQL     │ Redis            │ Volumen:       │ Horizon          │   │
│  │ (datos         │ (cache, colas,   │ /proyectos     │ Workers          │   │
│  │  compartidos)  │  sesiones)       │ (carpetas      │                  │   │
│  │                │                  │  locales user) │                  │   │
│  └───────────────┴──────────────────┴────────────────┴──────────────────┘   │
└──────────────────────────────────────────────────────────────────────────┘
         ▲                        ▲              ▲
         │                        │              │
   Spec Designer         Agente Programador  Agente Auditor
```

### Despliegue

| Modo | BD | Archivos | Usuarios |
|---|---|---|---|
| **Local (Docker)** | PostgreSQL en contenedor o remoto | Carpeta local del PC | Mono o multi-usuario local |
| **Servidor** | PostgreSQL centralizado | Cada usuario apunta a su carpeta local/proyectos | Multi-usuario con autenticación Laravel |

> Los archivos de proyecto (.spec.md, código fuente) son locales a cada usuario o se comparten vía Git/carpeta de red. La BD PostgreSQL centraliza metadatos, relaciones, estado de tests y configuración. No hay conflicto porque los archivos y la BD son independientes.

### Infraestructura de Servicios

| Servicio | Rol |
|---|---|
| **Nginx** | Proxy reverso, sirve frontend estático y enruta peticiones PHP a PHP-FPM. |
| **PHP-FPM** | Ejecuta Laravel. Imagen propia (`said-php`) con extensiones: pdo_pgsql, redis, pcntl. |
| **PostgreSQL 16** | Base de datos relacional. Almacena usuarios, sesiones, cache, jobs, y metadatos del sistema. |
| **Redis 7** | Cache, driver de colas para Horizon, y almacén de sesiones. |
| **Horizon** | Workers de Laravel para procesos asíncronos (tests, generación specs, auditoría). Usa Redis como backend. |

### Puesta en Marcha

```bash
cd app/
cp .env.example .env      # First time only — creates the env file for Docker Compose
docker compose up -d                  # Levanta todos los servicios
docker compose exec php php artisan migrate   # Crea tablas en PostgreSQL
docker compose exec php php artisan db:seed   # Usuario inicial (seeder)
```

> El `docker-compose.yml` está en `app/docker-compose.yml`. La estructura completa de carpetas está documentada en [SAID-Stack-Specification.md](SAID-Stack-Specification.md).

---

## Tipos de IA

SAID utiliza tres IAs especializadas, cada una con un rol distinto en el flujo de trabajo.

### 📐 Spec Designer
- Ayuda al usuario a **pensar, estructurar y refinar** el algoritmo de una funcionalidad.
- Convierte ideas informales ("quiero que el login valide email y mande 2FA") en specs bien formadas con entradas, pasos, dependencias y salidas.
- Sugiere dependencias, detecta ambigüedades y propone mejoras antes de escribir código.
- No genera código — solo especifica. Es el arquitecto de la funcionalidad.
- Se asocia a un proyecto concreto.

### 💻 Agente Programador (creado por el usuario)
- **Lee la spec** del Spec Designer (o escrita manualmente) y genera el código que la implementa.
- Especializado en un stack tecnológico (Laravel, React, Python, etc.).
- Genera el código y la referencia exacta (archivo + línea + función/método).
- El usuario define: nombre, proveedor IA (OpenAI, Claude, Gemini), credenciales, especialidad, aplicación asociada.

### 🔍 Agente Auditor (creado por el usuario)
- **Compara el código generado contra el algoritmo** de la spec.
- Misma especialidad técnica que el programador, pero **no es el mismo agente**.
- Toma la referencia de código (archivo + línea + función) y verifica paso a paso.
- Reporta discrepancias: "El paso 3 del algoritmo dice X, pero el código hace Y".
- Sin sesgo de autoría — no escribió el código que audita.

> La creación de proyectos, aplicaciones y organización del árbol se hace manualmente desde la UI. No se requiere IA para tareas de gestión.

---

---

## Jerarquía del Proyecto

Todo proyecto SAID se organiza así:

```
Project                          ← Contenedor raíz (ruta en disco)
 └── App                         ← Entregable (web, mobile, api...)
      ├── screens/               ← Agrupaciones visuales
      │    ├── login.screen.md       ← Mockup ligero + refs a Functions
      │    ├── register.screen.md
      │    └── dashboard.screen.md
      └── modules/               ← Lógica de negocio
           └── auth/             ← Módulo: agrupa Functions relacionadas
                ├── login.spec.md         ← Function: algoritmo + test
                ├── register.spec.md
                ├── sendResetEmail.spec.md
                └── verify2faCode.spec.md
```

### Screen

Una **Screen** es la idea visual inicial de una interfaz. No contiene lógica de negocio, solo:

- Mockup ligero (wireframe, descripción de layout).
- Lista de **referencias a Functions** que la componen.
- Relaciones con modales, sub-screens o navegación.

```
Screen "Login"
 ├── Input: email
 ├── Input: password
 ├── Button "Sign in"      → ref a Function login()
 ├── Link "Forgot?"         → ref a Function sendResetEmail()
 └── Modal "2FA"           → ref a Function verify2faCode()
```

- Una Screen puede referenciar Functions de **múltiples módulos**.
- Una Function puede ser referenciada por **múltiples Screens** (web, mobile, API).
- Si una modal tiene lógica propia compleja, puede ser una sub-Screen o Screen independiente.

### Module y Function

Un **Module** agrupa Functions por dominio (auth, billing, users…).

Una **Function** es la unidad atómica de SAID:

- Se define en un archivo `.spec.md` con algoritmo, dependencias, test y referencia de código.
- **Existe una sola vez** en el árbol. Otras partes la referencian.
- No sabe de Screens. La Screen la referencia a ella, no al revés.

| Relación | Cómo se modela |
|---|---|
| Function llamada desde varias Screens | Cada Screen la referencia en su lista |
| Una Function depende de otra | Se declara en `## 🔗 Dependencias` del `.spec.md` |
| Varias Functions comparten un servicio | Ese servicio es otra Function referenciada como dependencia |

---

## Backend: Patrón Adaptadores + Capas

```
Controlador (HTTP)
     │
     ▼
Servicio (Lógica de negocio)
     │
     ├──► Repositorio (Datos: PostgreSQL / Archivos)
     │
     └──► Adaptadores (Conexiones externas)
              ├── IA Adapter (OpenAI, Claude, Gemini)
              ├── Git Adapter (comandos shell)
              ├── Filesystem Adapter (lectura/escritura de archivos)
              └── Command Adapter (ejecución de tests, procesos)
```

### Capas

| Capa | Responsabilidad |
|---|---|
| **Auth** | Registro, login, roles y permisos de usuarios. Middleware de autenticación en todas las rutas. |
| **Controlador** | Recibe peticiones HTTP, valida entrada, delega en servicios, retorna JSON. |
| **Servicio** | Lógica de negocio pura. Orquesta operaciones entre repositorios y adaptadores. |
| **Repositorio** | Acceso a datos (PostgreSQL para metadatos, filesystem para archivos .spec.md). |
| **Adaptador** | Abstracción de servicios externos (IA, Git, sistema de archivos, comandos). |

### Principio
- Los **servicios** dependen de **interfaces** (no de implementaciones concretas).
- Los **adaptadores** inyectan esas interfaces.
- Fácil de testear y reutilizar: cambiar de proveedor de IA solo implica cambiar el adaptador.
- La **autenticación** es obligatoria para acceder a cualquier endpoint de la API.

---

## Frontend

| Componente | Rol |
|---|---|
| **DaisyUI** | Componentes UI sin JS propio (modal, drawer, dropdown, tab, collapse, tooltip). Extiende Tailwind con clases semánticas. |
| **Alpine.js** | Reactividad y lógica de negocio (estados, submits, datos). Consume APIs REST. No se usa para componentes UI. |
| **Componentes reutilizables** | Fragmentos Blade + DaisyUI encapsulados (tablas, formularios, árbol, editor de algoritmo). |

- No hay Blade tradicional con lógica de backend. Blade solo sirve el layout inicial.
- Todo el contenido se carga vía APIs con Alpine.js.
- El frontend es **solo consumo de APIs**, igual que las IAs.

### Alpine.js + Blade: reglas de binding

Los prefijos de Alpine (`x-bind:`, `:`, `x-on:`, `@`) esperan expresiones JavaScript válidas. **Nunca** usar variables de Blade o PHP dentro de directivas Alpine:

```blade
{{-- ❌ Incorrecto — $project->path no es JS válido, Alpine crashea y reinicializa componentes --}}
<td :title="$project->path">{{ $project->path }}</td>

{{-- ✅ Correcto — atributo HTML estándar con echo de Blade --}}
<td title="{{ $project->path }}">{{ $project->path }}</td>

{{-- ✅ Correcto — si se requiere Alpine, pasar el valor como string literal --}}
<td :title="'{{ $project->path }}'">{{ $project->path }}</td>
```

| Regla | |
|---|---|
| Atributos con `:` o `x-bind:` | Solo expresiones JS. Usar `'{{ $var }}'` (con comillas) para inyectar valores PHP como strings |
| Atributos HTML sin `:` | Usar `{{ $var }}` normalmente |
| `@` o `x-on:` | Solo expresiones JS, no PHP |

> Un error de sintaxis en cualquier expresión Alpine (como `$project->path`) rompe el parser de Alpine y causa que componentes como `x-data`, `x-init` y `x-show` se reinicialicen, produciendo efectos secundarios como toasts duplicados, eventos dobles, o pérdida de estado.

### Humanización de identificadores

Los valores almacenados en base de datos usan snake_case y minúsculas (`mission_critical`, `ecommerce`, `b2b`). Para mostrarlos en la UI **nunca** se deben crear arrays de mapeo repetidos en cada vista. En su lugar se usa el helper centralizado `App\Support\Label::humanize()`:

```php
// ❌ Incorrecto — arrays repetidos en vistas
$labels = ['internal' => 'Internal', 'b2b' => 'B2B', ...];

// ✅ Correcto — helper centralizado
{{ \App\Support\Label::humanize($project->criticality) }}
```

**Funcionamiento**:
- La clase `Label` contiene un map interno con los valores que no pueden derivarse automáticamente (`ecommerce` → `E-commerce & Retail`, `b2b` → `B2B`, `gdpr` → `GDPR`, `pci_dss` → `PCI-DSS`, etc.).
- Si el valor no está en el map, aplica fallback automático: `ucwords(str_replace('_', ' ', $value))` — suficiente para casos como `internal` → `Internal` o `mission_critical` → `Mission Critical`.
- Si un valor nuevo no se resuelve bien con el fallback, solo se agrega al map en un solo lugar.
- El método `Label::options('field')` devuelve los pares `value => label` completos para poblar selects, checkboxes y radios. **Nunca** hardcodear opciones en la vista.

```blade
{{-- ❌ Incorrecto — opciones quemadas --}}
<select>
    <option value="finance">Finance & Banking</option>
    <option value="healthcare">Healthcare</option>
</select>

{{-- ✅ Correcto — desde Label --}}
<select>
    @foreach (\App\Support\Label::options('business_sector') as $val => $label)
        <option value="{{ $val }}" {{ old('field', $model->field ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
    @endforeach
</select>
```

| Regla | |
|---|---|
| Ubicación | `app/Support/Label.php` |
| Métodos | `Label::humanize(string $value): string` — valor individual a label |
| | `Label::options(string $field): array` — todas las opciones de un campo |
| Cuándo usarlos | `humanize` para mostrar un valor en UI; `options` para poblar selects/checkboxes/radios |

---

## Módulo de Usuarios y Autenticación

SAID incluye un sistema de usuarios a nivel de aplicación Laravel:

| Componente | Descripción |
|---|---|
| **Registro** | Creación de cuenta con email/contraseña (o invitación). |
| **Login** | Autenticación vía sesión o token (Sanctum para API). |
| **Roles** | Admin, Programador, Auditor (mínimo). Extensible. |
| **Permisos** | Acceso a proyectos, apps, módulos según rol. |
| **Middleware** | `auth` en todas las rutas API. Middleware de rol para endpoints sensibles. |

> La autenticación es propia de Laravel (no depende de servicios externos). Cada usuario se conecta a la misma BD PostgreSQL y ve los metadatos compartidos del proyecto, mientras sus archivos residen en su filesystem local.

## Ejecución de comandos

Cada proyecto puede usar stacks distintos (PHP, Rust, Python…). SAID no puede ejecutar esos comandos desde dentro del contenedor PHP. Para resolverlo, cada proyecto define su **executor**:

| Executor | Cómo funciona | Pide |
|---|---|---|
| **Docker** | SAID lanza contenedores efímeros vía `docker.sock` montando el volumen del proyecto | Ruta del `docker-compose.yml` |
| **Agent** | Un binario ligero instalado en el host escucha comandos de SAID | `host:port` del agente |

> Se configura al crear el proyecto. SAID envía el comando (`cargo test`, `pytest`, `npm run build`) al executor elegido y recoge el output.

## Modelo de Datos

### Regla de migraciones

Toda tabla debe incluir estos campos base:

```php
$table->id();                          // serial primary key
$table->foreignId('user_who_created_id')->nullable()->constrained('users');
$table->foreignId('user_who_updated_id')->nullable()->constrained('users');
$table->foreignId('user_who_deleted_id')->nullable()->constrained('users');
$table->timestamps();                  // created_at, updated_at
$table->softDeletes();                 // deleted_at
```

| Regla | Convención |
|---|---|
| Nombres de tabla | Inglés, plural (`projects`, `apps`, `modules`) |
| Llaves foráneas | Singular con sufijo `_id` (`user_id`, `project_id`, `app_id`) |
| `id` | Siempre `$table->id()` (bigint serial) |
| Auditoría | `user_who_created_id`, `user_who_updated_id`, `user_who_deleted_id` — FK a `users` |
| Timestamps | `created_at`, `updated_at` vía `$table->timestamps()` |
| Soft deletes | `deleted_at` vía `$table->softDeletes()` |

### Tablas

## Rutas / APIs

*(por definir — incluyen prefijo /api con middleware auth)*

### Regla de nomenclatura

Toda ruta debe definirse con `->name()`. Las referencias a rutas en controladores, vistas, middleware y redirects deben usar `route('name')`, nunca URLs hardcodeadas.

```php
// ✅ Correcto
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
return redirect()->route('projects.index');

// ❌ Incorrecto
Route::get('/projects', [ProjectController::class, 'index']);
return redirect('/projects');
```

| Tipo | Convención |
|---|---|
| Listado | `{resource}.index` |
| Formulario crear | `{resource}.create` |
| Guardar | `{resource}.store` |
| Ver | `{resource}.show` |
| Formulario editar | `{resource}.edit` |
| Actualizar | `{resource}.update` |
| Eliminar | `{resource}.destroy` |

## Línea Gráfica

> *Estándar visual del sistema. Toda pantalla nueva debe seguir estas reglas.*

---

### Paleta de Colores

Extraída de la mascota SAID. Definida en `public/css/said.css` como CSS custom properties.

| Nombre | Hex | Rol |
|---|---|---|
| **Navy** | `#133d5b` | Sidebar, encabezados, degradados oscuros |
| **Navy light** | `#1a4d72` | Hover sutil sobre navy |
| **Teal** | `#4eaccf` | Botones principales, links, acentos activos, borde hover de cards (`border-teal/60`) |
| **Teal light** | `#97e1f7` | Highlights, badges activos, íconos sobre fondos oscuros |
| **Teal dark** | `#3b8aa8` | Hover de botones teal |
| **Peach** | `#f5c798` | Acentos cálidos secundarios (ícono Projects) |
| **Coral** | `#d94e51` | Errores, hover de logout, acciones destructivas |
| **Warm gray** | `#8f8370` | Texto secundario, descripciones |
| **Cool gray** | `#c1cbce` | Bordes de cards (`border-cool/50`), placeholders, texto inactivo |
| **Fondo página** | `#ffffff` | Fondo general del contenido principal |

### Degradados

Los paneles oscuros (sidebar, panel izquierdo de login/install) usan degradado **siempre con `style=` inline**, nunca con clases Tailwind:

```css
background: linear-gradient(to bottom, #133d5b, #000508);
```

| Dirección | Uso |
|---|---|
| `to bottom` | Sidebar |
| `to bottom right` | Panel izquierdo de login e install |
| `to right` | Banners (obsoleto — reemplazado por cards blancas) |

### Tipografía

| Elemento | Tamaño | Peso | Clase Tailwind |
|---|---|---|---|
| Heading de página (top bar) | 18px | semibold | `text-lg font-semibold` |
| Título de sección | 16px | semibold | `text-base font-semibold` |
| Título de card | 14px | semibold | `text-sm font-semibold` |
| Texto cuerpo / descripción | 14px | normal | `text-sm` |
| Texto secundario | 14px | normal | `text-sm text-warm` |
| Badges "soon" / "Coming soon" | 11-12px | medium | `text-[11px]` / `text-xs` |
| Sidebar secciones | 12px | semibold | `text-xs font-semibold` |
| Sidebar ítems | 14px | medium | `text-sm font-medium` |

- Familia: **Inter** (fallback: system-ui, -apple-system, sans-serif).
- Todo en **inglés**.

### Layouts

#### Layout autenticado (`layouts/app.blade.php`)

```
┌──────────────┐ ┌──────────────────────────────────────────────┐
│ Sidebar      │ │ Top bar (header)                             │
│ 240px        │ ├──────────────────────────────────────────────┤
│ degradado    │ │                                              │
│ navy→negro   │ │ max-width: 1280px · mx-auto · px-8 · py-6    │
│              │ │                                              │
│ [logo] SAID  │ │ @yield('content')                            │
│              │ │                                              │
│ MAIN         │ │                                              │
│ · Dashboard  │ │                                              │
│              │ │                                              │
│ WORKSPACE    │ │                                              │
│ · Projects   │ │                                              │
│ · AI Agents  │ │                                              │
│              │ │                                              │
│ SETTINGS     │ │                                              │
│ · API Tokens │ │                                              │
│              │ │                                              │
│ [user]       │ │                                              │
└──────────────┘ └──────────────────────────────────────────────┘
```

- Sidebar: ancho fijo 240px (`w-60`), degradado navy→negro, texto blanco.
- Contenido: fondo blanco, ancho máximo 1280px centrado con `mx-auto`.
- Top bar: fondo blanco, borde inferior cool-gray, altura compacta (`py-2.5`).

#### Layout público — login / install / welcome

```
┌─────────────────────┐ ┌──────────────────────┐
│ Panel izquierdo     │ │ Panel derecho        │
│ Degradado navy→     │ │ Fondo blanco         │
│ negro (to bottom    │ │ Formulario /         │
│ right)              │ │ contenido            │
│                     │ │                      │
│ [pet.png]           │ │                      │
│ SAID                │ │                      │
│ Steps (install)     │ │                      │
└─────────────────────┘ └──────────────────────┘
```

- Two-panel: izquierdo 50% con degradado oscuro + mascota, derecho 50% blanco con formulario.
- Ancho máximo del contenedor: `max-w-4xl` (896px en desktop).

### Componentes

#### Hero (dashboard)

Card blanca con borde inferior teal de 4px. Pet a la izquierda (56px), saludo + descripción, CTAs a la derecha.

```
┌──────────────────────────────────────────────────────────┐
│ [pet]  Hello, Name!                     [Create Project] │
│        Description...                    [Write Spec]    │
│━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━│ ← teal 4px
└──────────────────────────────────────────────────────────┘
```

#### Cards "Getting started"

Grid 2×2. Cada card: borde `cool/50`, hover borde `teal/60` + sombra. Ícono 36px en bg de color + título + descripción breve + badge "Coming soon".

#### Badges de estado

- **Sidebar**: pills redondeadas `text-[11px] px-1.5 py-0.5 rounded-full bg-white/10 text-white/40`
- **Cards**: pills `text-xs px-2 py-0.5 rounded-full bg-slate-50 border border-cool/30 text-cool`

#### Botones

| Tipo | Clases |
|---|---|
| Primario | `bg-teal text-white px-4 py-2 rounded-lg hover:bg-teal-dark` |
| Secundario | `border border-cool text-navy px-4 py-2 rounded-lg hover:border-teal hover:text-teal` |

**Regla obligatoria — Submit buttons**: Todo `<button type="submit">` debe bloquearse tras el primer click para prevenir dobles envíos. El patrón es Alpine.js inline, con el estado en el `<form>` (no en el botón):

```html
<form action="..." method="POST"
      x-data="{ submitting: false }"
      @submit="submitting = true">
    @csrf
    ...
    <button type="submit"
            :disabled="submitting"
            class="bg-teal text-white rounded-lg px-6 py-2.5 text-sm font-medium hover:bg-teal-dark transition shadow-sm disabled:opacity-60 disabled:cursor-not-allowed">
        <span x-show="!submitting">Create project</span>
        <span x-show="submitting" class="flex items-center gap-2">
            <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            Creating&hellip;
        </span>
    </button>
</form>
```

- `x-data="{ submitting: false }"` **en el `<form>`**, no en el botón.
- `@submit="submitting = true"` en el form — Alpine captura el evento pero **no previene** el submit nativo. El navegador ya inició el envío antes de que el botón se deshabilite visualmente, así que no hay race condition.
- `:disabled="submitting"` en el botón — evita doble click y activa los estilos `disabled:`.
- El `<span x-show="!submitting">` muestra el label original, el otro `<span>` muestra el spinner + texto de progreso.

> **Regla**: No usar entidades HTML (`&hellip;`, `&mdash;`, etc.) dentro de `{{ }}` de Blade. Blade escapa el output con `htmlspecialchars`, convirtiendo `&hellip;` en `&amp;hellip;` (visible literalmente). Usar el carácter Unicode directamente (`…`, `—`) o `{!! !!}` si es imprescindible.

#### Tooltips (DaisyUI)

Las clases `tooltip` de DaisyUI aplican `position: relative` al elemento, lo que interfiere con componentes que usan `display: inline-flex` (como `btn`), desplazando su contenido interno. **Siempre** se debe envolver el elemento en un `<span>` contenedor que lleve las clases tooltip:

```html
{{-- ❌ Incorrecto — tooltip directo sobre btn rompe el layout --}}
<a href="#" class="btn btn-primary btn-sm tooltip tooltip-bottom" data-tip="Ctrl+N">...</a>

{{-- ✅ Correcto — tooltip en wrapper --}}
<span class="tooltip tooltip-bottom" data-tip="Ctrl+N">
    <a href="#" class="btn btn-primary btn-sm">...</a>
</span>
```

| Regla | |
|---|---|
| Clases tooltip | Siempre en `<span>` o `<div>` contenedor, nunca en el propio `<a>` o `<button>` con clase `btn` |
| `data-tip` | En el mismo elemento contenedor que tiene las clases tooltip |

#### Empty state

Cuando una entidad no tiene datos, mostrar siempre una guía visual:

```
┌──────────────────────────────────────────────────────────┐
│                                                          │
│                    [ ícono 64px en círculo teal/15 ]     │
│                                                          │
│                    No projects yet                       │
│           Projects are the top-level containers...       │
│                                                          │
│                 [ Create your first project ]            │
│                                                          │
└──────────────────────────────────────────────────────────┘
```

- Ícono representativo de la entidad (carpeta, pantalla, agente…).
- Círculo `w-16 h-16 rounded-full bg-teal/15` con ícono `text-teal w-8 h-8`.
- Título `text-lg font-bold text-navy`.
- Descripción `text-sm text-warm max-w-md mx-auto` explicando qué es la entidad.
- CTA primario con verbo claro: "Create your first…".
- Todo centrado con `max-w-2xl mx-auto text-center py-16`.

### Arquitectura CSS

| Capa | Archivo | Qué contiene |
|---|---|---|
| **Tailwind CDN** | `cdn.tailwindcss.com` | Utilidades estándar: layout, spacing, tipografía, flex, grid |
| **said.css** | `public/css/said.css` | Colores custom (CSS custom properties + clases utilitarias + opacidades), estilos globales (body, scrollbar, focus rings, button:disabled) |
| **Degradados** | `style="..."` inline | Paneles oscuros (navy → `#000508`). No usar clases Tailwind para degradados con colores custom |

> **Regla**: Los colores de la paleta NO se configuran en `tailwind.config` (el CDN no lo resuelve de forma fiable). Van exclusivamente por `said.css` como clases `.bg-teal`, `.text-navy`, `.border-cool`, etc. con todas sus variantes de opacidad (`.bg-teal/15`, `.text-teal-light/70`, etc.).

### Principios de diseño

- **Sencillo y accionable**: El dashboard debe sentirse como herramienta, no como landing decorativa.
- **Menos texto**: Cards con descripciones de 1 línea. Sin párrafos largos.
- **Jerarquía clara**: Sidebar oscuro, contenido blanco, CTAs en teal.
- **Estados visibles**: Badges "soon" / "Coming soon" con estilo consistente en sidebar y cards.
- **Solo Desktop**: Mínimo 1024px de ancho. No responsive para móvil.
- **Sidebar siempre visible**: Navegación jerárquica presente en todo momento (árbol de proyectos en el futuro).
- **Empty state obligatorio**: Toda entidad (proyectos, apps, módulos, funciones, agentes…) debe tener una pantalla de índice que, cuando no hay datos, muestre un empty state con ícono, título descriptivo ("No projects yet"), explicación breve de qué es la entidad y un CTA claro para crear la primera. Nunca una tabla vacía o un espacio en blanco.

### Modales

Todo modal debe usar los componentes de **DaisyUI**:

- Usar `modal` + `modal-box` + `modal-action` + `modal-backdrop`.
- Abrir/cerrar con el método `showModal()` / `close()` del elemento `<dialog>` nativo.
- Alpine.js se limita al estado de apertura (`x-data="{ open: false }"`, `@click="open = true"`, `$el.showModal()`), sin lógica de overlay, blur ni teletransporte.
- El `x-data` del componente debe estar fuera de cualquier `<form>` para evitar conflictos de scope.
