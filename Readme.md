# 🧠 SAID — Spec-Aligned AI Development

> *Co-especificación y co-construcción con IA*

---

## 📜 Tabla de Contenidos

1. [El Problema](#el-problema)
2. [¿Qué es SAID?](#qué-es-said)
3. [Pilares Fundamentales](#pilares-fundamentales)
4. [Estructura del Proyecto](#estructura-del-proyecto)
5. [La Especificación de una Funcionalidad](#la-especificación-de-una-funcionalidad)
6. [Reglas del Juego](#reglas-del-juego)
7. [Flujo de Trabajo](#flujo-de-trabajo)
8. [Roles](#roles)
9. [Beneficios](#beneficios)
10. [Glosario](#glosario)

---

## El Problema

El desarrollo de software con IA tiene un vacío fundamental:

> **La IA no retiene contexto entre sesiones, y el programador olvida los detalles con el tiempo. No existe un estándar que sirva como "memoria persistente" compartida entre ambos.**

Esto genera:
- ❌ En cada nuevo chat, la IA reinventa soluciones desde cero.
- ❌ No hay una definición clara y acordada de lo que debe hacer cada funcionalidad.
- ❌ Los tests no están vinculados a las funcionalidades, imposibilitando verificar que siguen funcionando.
- ❌ El código se duplica porque la IA (o el programador) no sabe que ya existe una funcionalidad similar.
- ❌ No hay trazabilidad: "¿dónde está?, ¿qué hace?, ¿por qué se hizo así?"

---

## ¿Qué es SAID?

**Spec-Aligned AI Development (SAID)** es una metodología de desarrollo de software donde:

1. **La especificación (spec) es el artefacto principal** — no el código.
2. **El algoritmo es la fuente de verdad** — describe qué debe hacer la funcionalidad en un lenguaje natural estructurado, entendible por humanos e IA por igual.
3. **Los tests verifican el funcionamiento** — no el algoritmo, sino que la funcionalidad opera correctamente.
4. **El código es generado por IA** — alineado al algoritmo definido en la spec.
5. **Una capa superior persistente** almacena, organiza y relaciona todas las especificaciones, dando memoria compartida a la IA y al programador.

SAID no es una herramienta. Es un **estándar** que puede ser implementado con cualquier stack tecnológico.

---

## Pilares Fundamentales

### 1. 📐 El Algoritmo Manda
El algoritmo escrito en la especificación es la fuente de verdad. El código debe implementarlo fielmente. Si hay discrepancia, el algoritmo prevalece.

> El código puede tener múltiples interpretaciones y variaciones. El algoritmo es concreto.

### 2. 🧩 Una Funcionalidad, Un Lugar
Cada funcionalidad se define **una sola vez** en un único módulo. Si otra funcionalidad la necesita, **la referencia como dependencia** — nunca la duplica.

### 3. 🧪 Test como Verificador
El test no valida el algoritmo directamente. Valida que la funcionalidad **funcione correctamente**. Para verificar la alineación con el algoritmo, se usa una IA auditora que compara el código contra la spec usando la referencia (archivo + línea + nombre de función/método).

### 4. 🗂️ Capa Superior Persistente
Existe una plataforma por encima que:
- Almacena y organiza proyectos, aplicaciones, módulos y funcionalidades.
- Guarda las relaciones entre funcionalidades (quién llama a quién).
- Permite ejecutar tests y llevar control de resultados.
- Sirve como "memoria" compartida entre la IA y el programador.
- Facilita la comunicación con la IA para crear, modificar o consultar especificaciones.

### 5. 🔗 Dependencias Explicitas
Cada funcionalidad declara explícitamente de qué otras funcionalidades depende. Esto permite:
- Saber el impacto de un cambio.
- Mockear dependencias en tests unitarios.
- Reconstruir el grafo completo del sistema.

---

## Estructura del Proyecto

Todo proyecto SAID se organiza jerárquicamente así:

```
proyecto/
├── apps/                          ← Aplicaciones del proyecto
│   └── app-web/
│       └── modulos/               ← Módulos de la aplicación
│           ├── auth/              ← Un módulo
│           │   ├── funcionalidades/  ← Funcionalidades del módulo
│           │   │   ├── login.spec.md
│           │   │   ├── logout.spec.md
│           │   │   └── registro.spec.md
│           │   └── modulos/       ← Submódulos (misma estructura)
│           │       └── .../
│           ├── usuarios/
│           └── facturacion/
└── global/
    └── specs/                     ← Especificaciones compartidas/globales
```

### Reglas de estructura:
- **Proyecto**: Contiene una o más aplicaciones.
- **Aplicación**: Representa un entregable (web, móvil, API, etc.).
- **Módulo**: Agrupa funcionalidades relacionadas. Puede contener submódulos.
- **Funcionalidad**: La unidad atómica. Se define **una sola vez** en el árbol y se referencia desde donde se necesite.

---

## La Especificación de una Funcionalidad

Cada funcionalidad se define en un archivo `.spec.md` con la siguiente estructura:

### Template

```markdown
# S-[ID]: [Nombre de la Funcionalidad]

## 📝 Descripción
Breve descripción de qué hace esta funcionalidad.

## 📐 Algoritmo

📥 Entrada: [parámetros que recibe]
📤 Salida: [lo que retorna]

1. [Paso 1 - acción concreta]
2. [Paso 2 - acción concreta]
3. SI [condición] → [acción]
4. SI NO [condición] → [acción]
5. Retornar [resultado]

## 🔗 Dependencias

| Funcionalidad | Tipo |
|---|---|
| `auth.buscarUsuario()` | directo |
| `email.enviarCorreo()` | mock |

> **Tipo**: `directo` = llama a la implementación real, `mock` = simula la dependencia (para tests).
> El valor por defecto en tests es `mock`. En producción es `directo`.

## 📍 Referencia de Código

| Campo | Valor |
|---|---|
| Archivo | `src/modulos/auth/login.js` |
| Línea | 15 |
| Función/Método | `login(email, password)` |

## 🧪 Test

| Campo | Valor |
|---|---|
| Archivo | `tests/auth/login.test.js` |
| Comando | `npm run test:auth -- login` |
| Dependencias en test | `buscarUsuario()` → mock, `enviarCorreo()` → directo |

## 📝 Notas
Cualquier información adicional relevante (decisiones técnicas, por qué se hizo así, etc.)
```

### Ejemplo real:

```markdown
# S-001: login()

## 📝 Descripción
Autentica a un usuario mediante email y contraseña, y retorna un token JWT.

## 📐 Algoritmo

📥 Entrada: email (string), password (string)
📤 Salida: { token: string, usuario: { id, nombre, email } }

1. Validar que el email tenga formato válido
2. Buscar usuario en BD por email
3. SI el usuario no existe → Retornar error "Usuario no encontrado"
4. SI existe → Comparar el password contra el hash almacenado en BD
5. SI el password no coincide → Retornar error "Credenciales inválidas"
6. SI coincide → Generar token JWT con expiración de 24h
7. Retornar { token, datos del usuario }

## 🔗 Dependencias

| Funcionalidad | Tipo |
|---|---|
| `usuarios.buscarPorEmail()` | directo |
| `auth.compararHash()` | directo |
| `auth.generarJWT()` | directo |

## 📍 Referencia de Código

| Campo | Valor |
|---|---|
| Archivo | `src/modulos/auth/login.js` |
| Línea | 15 |
| Función/Método | `login(email, password)` |

## 🧪 Test

| Campo | Valor |
|---|---|
| Archivo | `tests/auth/login.test.js` |
| Comando | `npm run test:auth -- login` |
| Dependencias en test | `usuarios.buscarPorEmail()` → mock, `auth.compararHash()` → directo |

## 📝 Notas
El token JWT incluye el id del usuario, rol y fecha de expiración. No se almacena en BD.
```

---

## Reglas del Juego

### 🔹 Regla 1: Unicidad de la Funcionalidad
Una misma funcionalidad **no puede existir en dos módulos diferentes**. Si se necesita en otro contexto, se referencia como dependencia.

### 🔹 Regla 2: El Algoritmo es la Verdad
Ante cualquier discrepancia entre el código y el algoritmo de la spec, **el algoritmo tiene prioridad**. El código debe corregirse para alinearse.

### 🔹 Regla 3: Test no es Verdad del Algoritmo
El test verifica que la funcionalidad **funciona**, no que está alineada al algoritmo. Para verificar alineación, se usa una **IA auditora** que compara código contra algoritmo usando la referencia (archivo + línea + función).

### 🔹 Regla 4: Nivel de Detalle Libre
Cada persona define el algoritmo al nivel de detalle que desee:
- Un **senior** puede ser muy específico y dejar todo atado.
- Alguien que **no conoce el lenguaje** puede describir la lógica genérica y que la IA lo implemente.
- No hay rigidez. El formato es lenguaje natural estructurado.

### 🔹 Regla 5: La Spec se Mantiene
Cuando se modifica una funcionalidad, **se actualiza la spec primero** (el algoritmo). Luego se genera/actualiza el código.

### 🔹 Regla 6: Trazabilidad Obligatoria
Toda funcionalidad debe tener su referencia de código (archivo + línea + función) para que tanto la IA como el programador lleguen directamente al código fuente.

---

## Flujo de Trabajo

```
                    ┌───────────────────────┐
                    │  1. DEFINIR SPEC       │
                    │  (Algoritmo + Test)    │
                    └──────────┬────────────┘
                               │
                               ▼
                    ┌───────────────────────┐
                    │  2. REVISAR SPEC       │
                    │  (Humano + IA)         │
                    └──────────┬────────────┘
                               │
                               ▼
                    ┌───────────────────────┐
                    │  3. GENERAR CÓDIGO     │
                    │  (IA basada en algo.)  │
                    └──────────┬────────────┘
                               │
                               ▼
                    ┌───────────────────────┐
                    │  4. EJECUTAR TEST      │
                    │  (Verificar funciona)  │
                    └──────────┬────────────┘
                               │
                    ┌──────────┴──────────┐
                    │                     │
                    ▼                     ▼
            ┌──────────────┐     ┌──────────────┐
            │  TEST OK     │     │ TEST FALLA   │
            │  → Siguiente │     │ → Corregir   │
            └──────────────┘     └──────────────┘
                                         │
                                         ▼
                              ┌──────────────────────┐
                              │  5. AUDITORÍA IA      │
                              │  (Código vs Algoritmo)│
                              └──────────────────────┘
```

### Pasos detallados:

1. **Definir Spec**: El programador o la IA escribe la especificación de la funcionalidad (algoritmo + test + dependencias).
2. **Revisar Spec**: Humano e IA revisan la spec para validar que el algoritmo es correcto.
3. **Generar Código**: La IA genera el código basándose en el algoritmo. Puede ser desde cero o modificando código existente.
4. **Ejecutar Test**: Se ejecuta el test vinculado. Si pasa, la funcionalidad opera correctamente.
5. **Auditar (opcional)**: Una IA auditora compara el código generado contra el algoritmo de la spec para verificar alineación.

---

## Roles

### 👤 Programador
- Define y revisa especificaciones.
- Toma decisiones sobre el algoritmo.
- Ejecuta tests y verifica resultados.
- Se apoya en la IA para implementar.

### 🤖 IA Generadora
- Lee la especificación (algoritmo + dependencias).
- Genera el código que implementa fielmente el algoritmo.
- Genera los tests según lo indicado en la spec.
- Reporta la ubicación exacta (archivo + línea + función).

### 🤖 IA Auditora
- Toma la referencia de código (archivo + línea + función).
- Compara el código implementado contra el algoritmo de la spec.
- Reporta discrepancias: "El paso 3 del algoritmo dice X, pero el código hace Y".

### 🗂️ Capa Superior (Plataforma)
- Almacena todas las especificaciones.
- Organiza proyectos, aplicaciones, módulos y funcionalidades.
- Gestiona dependencias entre funcionalidades.
- Ejecuta tests y lleva historial de resultados.
- Facilita la comunicación entre programador e IA.
- Permite consultar: "¿Qué funcionalidades existen?", "¿Cuáles dependen de X?", "¿Dónde está Y?".

---

## Beneficios

### Para el Programador
- ✅ **No necesita conocer el lenguaje** para definir qué hace una función.
- ✅ **No olvida** lo que hizo, porque la spec está siempre ahí.
- ✅ **Puede retomar** un proyecto después de meses sin perder contexto.
- ✅ **Sabe el impacto** de cambiar una funcionalidad (grafo de dependencias).

### Para la IA
- ✅ **No reinventa** en cada sesión. Lee la spec y sabe exactamente qué hacer.
- ✅ **No pierde el contexto** entre chats. La capa superior se lo recuerda.
- ✅ **Genera código alineado** a una intención concreta, no a una interpretación vaga.

### Para el Proyecto
- ✅ **Documentación viva** que no se desactualiza.
- ✅ **Trazabilidad completa**: desde la intención hasta el código y el test.
- ✅ **Calidad verificable**: los tests demuestran que las funcionalidades funcionan.
- ✅ **Escalabilidad**: un proyecto grande se organiza en módulos y funcionalidades sin duplicación.
- ✅ **Portabilidad**: cambiar de lenguaje es más fácil (solo se regenera el código, el algoritmo sigue siendo válido).

---

## Glosario

| Término | Definición |
|---|---|
| **SAID** | Spec-Aligned AI Development. Metodología descrita en este documento. |
| **Spec** | Especificación de una funcionalidad. Archivo `.spec.md` con algoritmo, test, dependencias y referencia de código. |
| **Algoritmo** | Secuencia de pasos en lenguaje natural estructurado que describe qué hace la funcionalidad. Fuente de verdad. |
| **Funcionalidad** | Unidad atómica del sistema. Se define una sola vez y se referencia desde otros lugares. |
| **Módulo** | Agrupación de funcionalidades relacionadas. Puede contener submódulos. |
| **Aplicación** | Entregable del proyecto (web, móvil, API, etc.). Contiene módulos. |
| **Dependencia** | Relación entre dos funcionalidades donde una llama a la otra. |
| **Mock** | Simulación de una dependencia para aislar el test de una funcionalidad. |
| **Capa Superior** | Plataforma que almacena, organiza y gestiona las especificaciones, tests y comunicación con la IA. |
| **IA Auditora** | IA que compara el código implementado contra el algoritmo de la spec para verificar alineación. |

---

## Licencia

Este estándar es abierto y libre de usar. SAID no es una herramienta ni un producto, es una metodología.
