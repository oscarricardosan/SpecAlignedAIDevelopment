# 🧠 SAID Architecture

> *Arquitectura del sistema de la plataforma SAID*

---

## Visión General

SAID se ejecuta en **Docker**, tanto en local como en servidor. Todo el sistema se comunica vía **APIs REST**, consumidas por el frontend, las IAs y otros usuarios de la plataforma.

La base de datos (PostgreSQL) reside en el servidor y es compartida por todos los usuarios que trabajan en un mismo proyecto. Los archivos de cada proyecto residen en el sistema de archivos **local de cada usuario** (o en una carpeta compartida/Git), sin conflictos con la BD centralizada.

```
🐳 Docker
┌──────────────────────────────────────────────────────────────────┐
│  Nginx                                                            │
│  ┌──────────────────┐  ┌────────────────────────────────────┐    │
│  │ Frontend           │  │ API REST (JSON)                    │    │
│  │ Alpine.js + Tail.  │  │ /api/proyectos                     │    │
│  │ Componentes reutil.│  │ /api/apps                          │    │
│  │                    │  │ /api/modulos                       │    │
│  │                    │  │ /api/funcionalidades               │    │
│  │                    │  │ /api/agentes                       │    │
│  │                    │  │ /api/tests                         │    │
│  │                    │  │ /api/chat                          │    │
│  │                    │  │ /api/git                           │    │
│  └──────────────────┘  └──────────┬─────────────────────────┘    │
│                                    │                               │
│                           ┌────────▼────────┐                     │
│                           │  Laravel Backend  │                     │
│                           │  ┌────────────┐   │                     │
│                           │  │ Auth        │   │  ← Usuarios, roles│
│                           │  ├────────────┤   │                     │
│                           │  │ Controlador │   │                     │
│                           │  ├────────────┤   │                     │
│                           │  │ Servicio    │   │                     │
│                           │  ├────────────┤   │                     │
│                           │  │ Repositorio │   │                     │
│                           │  ├────────────┤   │                     │
│                           │  │ Adaptadores  │   │                     │
│                           │  │ (IA, Git,   │   │                     │
│                           │  │  Filesystem)│   │                     │
│                           │  └────────────┘   │                     │
│                           └───────────────────┘                     │
│                                    │                                │
│  ┌───────────────┬─────────────────▼────────────┬──────────────┐   │
│  │ PostgreSQL     │ Volumen: /proyectos          │ Horizon      │   │
│  │ (datos         │ (carpetas locales del user)  │ Workers      │   │
│  │  compartidos)  │                              │              │   │
│  └───────────────┴──────────────────────────────┴──────────────┘   │
└──────────────────────────────────────────────────────────────────┘
         ▲                        ▲              ▲
         │                        │              │
   IA del Sistema        Agentes Programador   Agentes Auditor
```

### Despliegue

| Modo | BD | Archivos | Usuarios |
|---|---|---|---|
| **Local (Docker)** | PostgreSQL en contenedor o remoto | Carpeta local del PC | Mono o multi-usuario local |
| **Servidor** | PostgreSQL centralizado | Cada usuario apunta a su carpeta local/proyectos | Multi-usuario con autenticación Laravel |

> Los archivos de proyecto (.spec.md, código fuente) son locales a cada usuario o se comparten vía Git/carpeta de red. La BD PostgreSQL centraliza metadatos, relaciones, estado de tests y configuración. No hay conflicto porque los archivos y la BD son independientes.

---

## Tipos de IA

### 🤖 IA del Sistema (viene con SAID)
- Conoce la metodología SAID al 100%.
- Ayuda a crear proyectos, aplicaciones, módulos, funcionalidades.
- Se alimenta del contexto del sistema (descripciones, árbol, configuraciones).
- Única para toda la plataforma.

### 🤖 Agente Programador (lo crea el usuario)
- Especializado en tecnologías específicas (Laravel, React, Python, etc.).
- Genera código y tests basados en las specs.
- Se asocia a una aplicación concreta.
- El usuario define su nombre, personalidad, credenciales y especialidad.

### 🤖 Agente Auditor (lo crea el usuario)
- Misma especialidad técnica que el programador.
- **No es el mismo agente**: revisa el código generado vs el algoritmo de la spec.
- Reporta discrepancias sin el sesgo de haber escrito el código.

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
| **Alpine.js** | Reactividad y lógica del lado del cliente. Consume las APIs REST. |
| **Tailwind CSS** | Estilos utilitarios, componentes reutilizables con clases. |
| **Componentes reutilizables** | Fragmentos Blade + Alpine encapsulados (tablas, formularios, árbol, editor de algoritmo). |

- No hay Blade tradicional con lógica de backend. Blade solo sirve el layout inicial.
- Todo el contenido se carga vía APIs con Alpine.js.
- El frontend es **solo consumo de APIs**, igual que las IAs.

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

## Modelo de Datos

*(por definir — tablas principales: users, proyectos, aplicaciones, modulos, funcionalidades, agentes, tests, dependencias)*

## Rutas / APIs

*(por definir — incluyen prefijo /api con middleware auth)*

## Lineamientos Visuales (UI/UX)

### Principios
- **Sencillo y atractivo**: Nada recargado, colores suaves, tipografía clara.
- **Guía al usuario**: Mensajes y tooltips que expliquen los pasos, especialmente en la primera vez.
- **Árbol visible siempre**: La navegación jerárquica debe estar presente en todo momento.
- **Editor de algoritmo amigable**: Herramienta visual para modelar algoritmos sin escribir markdown crudo si no se quiere.

### Restricciones
- Solo **Desktop** (monitor). No responsive para celular.
- Diseñado para resolución mínima 1024x768.
- Layout con sidebar de navegación + contenido principal.
