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
