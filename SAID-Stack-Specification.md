# 🧠 SAID Stack Specification

> *Especificación del sistema que da vida a la metodología SAID*
>
> *Co-especificación y co-construcción con IA*

---

## Stack Tecnológico

### Backend

| Componente | Tecnología | Razón |
|---|---|---|
| Framework | **Laravel** | Cola de trabajos integrada, ORM, HTTP Client, Filesystem, Process. |
| Base de Datos | **PostgreSQL** | Soporte multi-usuario, acceso concurrente desde múltiples instancias, JSONB, MVCC, estándar con Laravel. |
| Cola de Procesos | **Laravel Horizon** | Workers nativos, monitoreo de cola, procesos en 2do plano. |

### Frontend

| Componente | Tecnología | Razón |
|---|---|---|
| CSS | **Tailwind CSS** | Utilidades rápidas, sin build complejo. |
| JavaScript | **Alpine.js** | Reactividad simple, sin compilación, se sirve directo. |

### Infraestructura

| Componente | Tecnología | Razón |
|---|---|---|
| Servidor Web | **Nginx + PHP-FPM** | Rendimiento, aislamiento, estándar moderno. |
| Contenedor | **Docker** | Fácil despliegue y portabilidad. |
| Cache y Colas | **Redis** | Backend de cache, sesiones y driver de colas para Horizon. |

---

## Estructura de Carpetas del Sistema

```
app/
├── docker-compose.yml               ← Orquestación de todos los servicios
├── data/                             ← Volúmenes persistentes
│   ├── redis/
│   └── postgres/
├── docker/                           ← Configuración de contenedores
│   ├── nginx/
│   │   └── default.conf             ← Configuración del servidor web
│   └── php/
│       └── Dockerfile               ← Imagen PHP-FPM con extensiones
├── assets/                           ← Recursos gráficos del proyecto
│   ├── favicon.png
│   └── pet.png
└── backend/                          ← Aplicación Laravel
    ├── app/
    ├── database/
    │   ├── migrations/               ← Esquema de tablas
    │   └── seeders/                  ← Datos iniciales (usuario admin)
    ├── routes/
    ├── config/
    ├── resources/
    ├── storage/
    └── tests/
```

### Servicios Docker

| Servicio | Imagen | Puerto | Descripción |
|---|---|---|---|
| `app` | nginx:stable-alpine | 80 | Servidor web, proxy hacia PHP-FPM |
| `php` | said-php (build local) | — | PHP-FPM 8.3 con extensiones pdo_pgsql, redis, pcntl |
| `horizon` | said-horizon (build local) | — | Workers de Laravel Horizon para procesos en 2do plano |
| `redis` | redis:7-alpine | 6379 | Cache y driver de colas para Horizon |
| `postgres` | postgres:16-alpine | 5432 | Base de datos PostgreSQL |

### Variables de Entorno de BD (docker-compose)

| Variable | Valor por defecto |
|---|---|
| `DB_CONNECTION` | `pgsql` |
| `DB_HOST` | `postgres` |
| `DB_PORT` | `5432` |
| `DB_DATABASE` | `said` |
| `DB_USERNAME` | `said` |
| `DB_PASSWORD` | `said_secret` |

---

## Proceso de Instalación

### Primer arranque

```bash
cd app/

# Create the Docker Compose env file from the template
cp .env.example .env

# Edit .env to set your projects root path
# SAID_ROOT=/your/projects/path

docker compose up -d
```

### Setup de base de datos y usuario

Una vez los contenedores están arriba:

```bash
# Ejecutar migraciones (crea tablas: users, sessions, cache, jobs)
docker compose exec php php artisan migrate

# Sembrar datos iniciales (crea usuario administrador por defecto)
docker compose exec php php artisan db:seed
```

> El seeder `DatabaseSeeder` crea un usuario de prueba. Para producción debe personalizarse con el usuario administrador real y roles del sistema (Admin, Programador, Auditor).

### Verificar instalación

```bash
# Salud de los servicios
docker compose ps

# Probar conexión a BD
docker compose exec php php artisan db:show

# Probar Redis
docker compose exec redis redis-cli ping
```

### Integraciones

| Componente | Descripción |
|---|---|
| **Git** | Integración nativa vía comandos shell (clone, add, commit, push, pull, status). |
| **APIs de IA** | OpenAI, Claude, Gemini o cualquier API compatible vía HTTP Client de Laravel. |

### Almacenamiento

| Dato | Dónde se guarda |
|---|---|
| Especificaciones (.spec.md) | **Archivos del proyecto** (en disco local del usuario, trackeados por Git). |
| Metadatos (proyectos, módulos, estado de tests, cola, usuarios) | **PostgreSQL** (índice y estado, compartido entre usuarios). |
| Código fuente del proyecto gestionado | **Archivos del proyecto** (en disco local, trackeado por Git). |

### Procesos en Segundo Plano

| Proceso | Worker |
|---|---|
| Ejecutar tests encolados | Laravel Horizon |
| Generar specs con IA | Laravel Horizon |
| Auditar código vs algoritmo | Laravel Horizon |

### Licencia

Apache 2.0
