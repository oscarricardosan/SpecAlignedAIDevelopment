# 🎬 Studio Module

> *Definición del módulo Studio — centro de diseño, ejecución y calidad de pantallas y funcionalidades*
>
> *Documento guía. Los detalles de implementación se definirán al abordar cada submódulo.*

---

## Visión General

Studio es el módulo donde los proyectos y aplicaciones cobran vida. Aquí se diseñan pantallas, se modelan funcionalidades con diagramas de flujo, y se orquesta su ejecución a través de IAs especializadas con control de calidad integrado.

### Flujo de entrada

```
Sidebar > Studio
    │
    ├── 1. Seleccionar Proyecto
    │       └── Dashboard de métricas del proyecto (estado de apps, tareas, colas)
    │
    └── 2. Seleccionar App
            │
            ├── 🖥️ Pantallas (Screens)
            │       └── Wireframe Editor → IA Programadora → IA Calidad UI
            │
            └── ⚙️ Funcionalidades (Functionalities)
                    └── Diagrama de Flujo → IA Programadora → IA Calidad (tests+lógica)
```

---

## 1. Pantallas (Screens)

### Wireframe Editor

Herramienta visual de diseño de pantallas. El usuario construye la interfaz arrastrando/ configurando componentes y el sistema persiste un **JSON estructurado** que sirve tanto para legibilidad de IA como para edición visual por el usuario.

### Formato JSON del Wireframe

```json
{
  "screen": {
    "id": "string",
    "name": "string",
    "layout": "column | row | grid",
    "components": [
      {
        "id": "string",
        "type": "input | button | select | textarea | label | card | table | modal | ...",
        "label": "string",
        "props": {}
      }
    ],
    "navigation": {
      "targets": ["screen_id"],
      "modals": ["screen_id"]
    },
    "references": ["functionality_id"]
  }
}
```

| Regla | Descripción |
|---|---|
| Legible por IA | JSON plano, sin anidamiento excesivo, props como objeto key-value |
| Editable por humano | El editor visual lo traduce a campos y drag & drop |
| Referencias | Una pantalla referencia funcionalidades, no al revés |
| Modals | Si tiene lógica propia compleja, puede ser una sub-pantalla independiente |

### Pipeline de ejecución — Pantalla

```
Wireframe JSON
    │
    ▼
IA Programadora ─── genera código de UI (vistas, componentes, estilos)
    │
    ▼
IA Calidad UI ─── evalúa desde rol de usuario
    │               ¿Es amigable? ¿Flujo claro? ¿Consistente con el diseño?
    ├── ✅ Pasa → Tarea completada
    └── ❌ Rechaza → Reintento (máx 3)
                        └── Agotados → Lista de verificación manual
```

---

## 2. Funcionalidades (Functionalities)

### Diagrama de Flujo

Editor visual de diagramas de flujo donde el usuario modela la lógica de negocio: entradas, pasos, decisiones, dependencias y salidas. El diagrama se persiste en un formato estructurado que la IA puede interpretar sin ambigüedad.

### Pipeline de ejecución — Funcionalidad

```
Diagrama de Flujo
    │
    ▼
IA Programadora ─── genera código (controladores, servicios, tests)
    │
    ▼
IA Calidad ─── verifica:
    │            ¿Tests pasan correctamente?
    │            ¿Código alineado al diagrama sin programar de más?
    │
    ├── ✅ Pasa → Tarea completada
    │              └── Si tiene parent_task_id → activa tarea hija
    └── ❌ Rechaza → Reintento (máx 3)
                        └── Agotados → Lista de verificación manual
```

---

## 3. AI Agents — Configuración

Los agentes se configuran en la sección **AI Agents** del sidebar. Aquí se define qué proveedor y modelo se usa **por cada nivel de dificultad**.

| Nivel | Proveedor | Modelo | Caso de uso típico |
|---|---|---|---|
| **Bajo** | A definir | A definir | Tareas simples, cambios pequeños |
| **Medio** | A definir | A definir | Funcionalidades estándar, CRUDs |
| **Alto** | A definir | A definir | Lógica compleja, integraciones, algoritmos |

### Detección automática de dificultad

El sistema **sugiere** el nivel de dificultad basándose en heurísticas objetivas del wireframe o diagrama de flujo. El usuario ve la sugerencia y puede ajustarla si lo considera necesario, pero no empieza desde cero.

| Heurística | Pantallas (Wireframe) | Funcionalidades (Diagrama) | Nivel sugerido |
|---|---|---|---|
| Simple | ≤ 5 componentes, sin lógica condicional ni navegación compleja | ≤ 3 pasos, sin decisiones ni ramificaciones | **Bajo** |
| Moderado | 6-15 componentes o layout con 1-2 secciones condicionales | 3-8 pasos, 1-2 decisiones o dependencias | **Medio** |
| Complejo | > 15 componentes, múltiples layouts condicionales, modals anidados | > 8 pasos, ≥ 3 decisiones, integraciones externas | **Alto** |

> **Regla**: El proveedor y modelo se determinan automáticamente según el nivel sugerido. El usuario solo interviene si quiere forzar un nivel distinto. Con el tiempo, si se detecta que ciertas tareas fallan consistentemente con el nivel sugerido, se puede entrenar un clasificador que refine la sugerencia basándose en resultados reales.

---

## 4. Motor de Ejecución

### Modos de ejecución

Al enviar una tarea (pantalla o funcionalidad), el usuario elige:

| Modo | Comportamiento |
|---|---|
| **Inmediato** | Se ejecuta ya, en el momento |
| **En cola** | Se encola en una de las colas de trabajo disponibles |
| **Después de otra tarea** | Se asocia a un `parent_task_id`. Se activa automáticamente cuando la tarea padre se completa con éxito |

### Dependencias entre tareas

- Modelo simple: `parent_task_id`.
- Una tarea hija **solo se activa si la padre termina exitosamente**.
- Si la padre falla (agota reintentos o va a lista manual), la hija **no se ejecuta**.
- Sin DAG, encadenamiento lineal.

---

## 5. Reintentos y Trazabilidad

### Lógica de reintentos

```
Intento 1: Programadora → Calidad → ❌
    │
    ▼ (inmediato, sin reencolar)
Intento 2: Programadora → Calidad → ❌
    │
    ▼ (inmediato, sin reencolar)
Intento 3: Programadora → Calidad → ❌
    │
    ▼
Lista de verificación manual
```

- Máximo **3 reintentos** por tarea.
- El reintento es **inmediato** (no vuelve a la cola).
- El reintento abarca **todo el pipeline** (Programadora + Calidad/UI), no solo la etapa fallida.
- Cada intento queda **registrado** (qué IA intervino, resultado, feedback de calidad, motivo de rechazo).

### Lista de verificación manual

Las tareas que agotan los 3 reintentos van a una lista especial donde un humano puede:
- Revisar el historial de intentos y logs.
- Corregir manualmente y reintentar.
- Cancelar la tarea.

---

## 6. Carga de Trabajo (Workload)

### Colas de trabajo

- **10 colas por defecto**.
- Cada cola es un worker independiente que permite paralelismo real.
- Las tareas se asignan a una cola al ser creadas.
- Orden **FIFO** dentro de cada cola.

### Comportamiento

| Escenario | Qué pasa |
|---|---|
| Tarea inmediata | Se asigna a una cola y se ejecuta en cuanto el worker está libre |
| Tarea encolada | Espera su turno en la cola asignada |
| Tarea con dependencia | No entra a cola hasta que la padre termina con éxito. Luego se asigna y ejecuta |

---

## 7. Dashboard de Métricas (Proyecto)

Al seleccionar un proyecto en Studio, se muestra un dashboard con métricas relevantes:

| Métrica | Descripción |
|---|---|
| Apps por estado | Cantidad de apps, pantallas y funcionalidades completadas / en progreso / pendientes |
| Tareas por cola | Distribución de tareas en las 10 colas |
| Tasa de éxito | % de tareas que pasan calidad en primer intento |
| Reintentos promedio | Intentos promedio por tarea (indicador de complejidad) |
| Tareas en lista manual | Pendientes de revisión humana |
| Tiempo promedio | Por nivel de dificultad (bajo/medio/alto) |

---

## 8. Trazabilidad — Task Logs

Cada tarea registra:

| Campo | Descripción |
|---|---|
| Tarea | ID, tipo (screen/functionality), dificultad, cola |
| Intento N | Número de intento (1-3) |
| IA Programadora | Proveedor, modelo, input, output, tiempo |
| IA Calidad / UI | Proveedor, modelo, resultado (✅/❌), feedback |
| Motivo de rechazo | Qué falló (test no pasó, UI no amigable, código no alineado al diagrama, etc.) |
| Estado final | Completada, fallida (lista manual), cancelada |

---

## 9. Integración con el Sistema

### Sidebar

```
MAIN
 · Dashboard
 · Studio          ← Nueva entrada

WORKSPACE
 · Projects
 · AI Agents       ← Nueva entrada (o extendida con la config de dificultad)

SETTINGS
 · API Tokens
```

### Relación con entidades existentes

| Entidad | Relación con Studio |
|---|---|
| **Project** | Se selecciona al entrar. Contiene métricas agregadas de sus apps |
| **Application** | Se selecciona tras el proyecto. Contiene pantallas y funcionalidades |
| **AI Agents** | Configuración de proveedores/modelos por nivel de dificultad |
| **Horizon** | Workers existentes para colas. Se evalúa si las 10 colas de Studio corren sobre Horizon o requieren workers dedicados |

---

## 10. Flujo completo

```
Studio
 │
 ├── Seleccionar Proyecto
 │     └── Dashboard métricas
 │
 ├── Seleccionar App
 │     │
 │     ├── 🖥️ Pantallas
 │     │     ├── Listado de pantallas de la app
 │     │     ├── Crear / Editar pantalla → Wireframe Editor
 │     │     └── Ejecutar → Modal: dificultad sugerida (ajustable) + modo (inmediato/cola/después de X)
 │     │
 │     └── ⚙️ Funcionalidades
 │           ├── Listado de funcionalidades de la app
 │           ├── Crear / Editar funcionalidad → Diagrama de Flujo
 │           └── Ejecutar → Modal: dificultad sugerida (ajustable) + modo (inmediato/cola/después de X)
 │
 ├── AI Agents (sidebar)
 │     └── Configurar proveedor + modelo por nivel (bajo, medio, alto)
 │
 └── Workload (sidebar o sección)
       └── Vista de las 10 colas, tareas pendientes, en progreso, historial
```

---

## Pendientes por Definir (al abordar cada submódulo)

- [ ] Estructura exacta del JSON de wireframe (tipos de componentes, props estándar)
- [ ] Formato del diagrama de flujo para funcionalidades
- [ ] Esquema de tablas (screens, functionalities, tasks, ai_agents, workload_queues, task_logs)
- [ ] API endpoints
- [ ] Vistas Blade + Alpine.js (wireframe editor, flow editor, dashboard métricas, workload)
- [ ] Integración con Horizon vs workers dedicados para las 10 colas
- [ ] Adaptadores de IA (capa de comunicación con proveedores externos)
- [ ] Mecanismo de re-evaluación en lista manual
