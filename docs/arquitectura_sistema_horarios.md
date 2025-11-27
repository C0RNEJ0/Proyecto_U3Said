# Documento de Arquitectura y Diseño: Sistema de Generación de Horarios UPV

**Versión:** 1.0
**Fecha:** 27 de Noviembre de 2025
**Autor:** Arquitecto de Software (IA)

---

## 1. Visión General del Proyecto

El objetivo es desarrollar una aplicación web para la **Universidad Politécnica de Victoria (UPV)** que automatice la generación de horarios escolares mediante algoritmos de **coloreado de grafos** y **backtracking**. El sistema sustituirá procesos manuales o basados en Excel, centralizando la información en una base de datos y ofreciendo una interfaz alineada con la identidad institucional.

### 1.1. Identidad Visual (UI/UX)
El diseño debe transmitir formalidad, eficiencia y pertenencia institucional.

*   **Paleta de Colores:**
    *   **Primario (Verde UPV):** `#006F45` (Cabeceras, botones principales, bordes activos).
    *   **Secundario (Gris Institucional):** `#54565A` (Textos secundarios, pies de página).
    *   **Fondo:** `#F4F6F8` (Gris muy claro para el cuerpo de la página).
    *   **Acento:** `#8DC63F` (Detalles, estados de éxito).
    *   **Blanco:** `#FFFFFF` (Tarjetas, contenedores de formularios).

*   **Tipografía:**
    *   Familia *Sans-Serif* moderna y legible (ej. *Roboto*, *Open Sans* o *Montserrat*).
    *   Tamaños: 14px para cuerpo, 16px para inputs, 24px para títulos de sección.

*   **Layout General:**
    *   **Header:** Barra superior fija, fondo `#006F45`. Logo UPV a la izquierda, título "Sistema de Generación de Horarios" en blanco.
    *   **Sidebar:** Menú lateral izquierdo colapsable, fondo `#E9ECEF` o blanco con items que se iluminan en verde al pasar el mouse.
    *   **Content:** Área central con "Cards" (tarjetas) para agrupar formularios y tablas.

---

## 2. Modelo de Datos (Diseño Conceptual)

El sistema se basa en 5 entidades principales que alimentan el grafo de conflictos.

### 2.1. Entidades
1.  **Profesores:** Recurso humano. Restricción principal: no puede estar en dos lugares a la vez.
2.  **Grupos:** Conjunto de alumnos (ej. ITI-1-1). Restricción: no pueden tener dos clases a la vez.
3.  **Aulas:** Espacio físico. Restricción: capacidad y disponibilidad.
4.  **Materias:** La unidad a programar. Relaciona Grupo + Profesor + Carga Horaria.
5.  **Bloques de Horario:** Los "colores" posibles para el grafo.

---

## 3. Definición Formal del Grafo de Conflictos

Para resolver el problema, modelamos el horario como un grafo $G = (V, E)$.

### 3.1. Vértices (Eventos)
Un vértice $v$ representa una **hora de clase individual** que debe ser impartida.
Si una materia $M$ (ej. "Programación") para el grupo $G$ con el profesor $P$ requiere $H$ horas a la semana, esta materia genera $H$ vértices distintos en el grafo.

$$V = \{ v_{m,i} \mid m \in \text{Materias}, 1 \le i \le m.\text{horas\_semana} \}$$

Cada vértice contiene la tupla de datos: `(id_materia, id_grupo, id_profesor, id_aula_tipo)`.

### 3.2. Aristas (Conflictos)
Una arista $(u, v) \in E$ existe si los eventos $u$ y $v$ **no pueden ocurrir en el mismo bloque de tiempo**.

Las reglas de conflicto son:

1.  **Conflicto de Profesor:**
    $$u.\text{profesor} = v.\text{profesor} \implies (u, v) \in E$$
    *Significado:* El mismo profesor no puede dar dos clases simultáneamente (ni al mismo grupo ni a distintos).

2.  **Conflicto de Grupo:**
    $$u.\text{grupo} = v.\text{grupo} \implies (u, v) \in E$$
    *Significado:* Un grupo no puede recibir dos materias al mismo tiempo.

3.  **Exclusión Mutua de Instancia (Materia Duplicada):**
    $$u.\text{materia} = v.\text{materia} \land u \neq v \implies (u, v) \in E$$
    *Significado:* Dos horas de la **misma materia** no pueden programarse en el mismo bloque horario (asumiendo bloques de 1 hora y que no queremos clases dobles en el mismo slot exacto, o para forzar distribución). *Nota: Si se permiten bloques continuos (clases de 2 horas), la lógica cambia ligeramente, pero para el modelo básico de "1 color = 1 hora", esto evita que la clase de Matemáticas se asigne 2 veces el Lunes a las 7:00.*

### 3.3. Colores (Bloques de Horario)
Sea $C$ el conjunto de colores disponibles, donde cada color representa un bloque único de tiempo (Día + Hora).
$$C = \{ c_1, c_2, ..., c_k \}$$
Ejemplo: $c_1 = \text{Lunes 07:00-08:00}$, $c_2 = \text{Lunes 08:00-09:00}$.

### 3.4. El Problema
Encontrar una función de asignación $f: V \rightarrow C$ tal que:
$$\forall (u, v) \in E, f(u) \neq f(v)$$

---

## 4. Algoritmo: Coloreado de Grafos + Backtracking

Utilizaremos un enfoque híbrido: **Heurística DSATUR (Degree of Saturation)** para ordenar las asignaciones y **Backtracking** para resolver conflictos difíciles.

### Pseudocódigo Detallado

#### Paso 1: Construcción
```text
FUNCION ConstruirGrafo():
  V = []
  PARA CADA materia M en BD:
    PARA i = 1 HASTA M.horas_semana:
      Crear nodo v = {id: generar_uuid(), materia: M, profesor: M.prof, grupo: M.grupo}
      Agregar v a V

  E = MatrizAdyacencia(tamaño V)
  PARA CADA par (u, v) en V:
    SI (u.profesor == v.profesor) O (u.grupo == v.grupo):
      E[u][v] = VERDADERO
      E[v][u] = VERDADERO
  RETORNAR (V, E)
```

#### Paso 2: Coloreado con Backtracking
```text
FUNCION ResolverHorario(V, E, BloquesDisponibles):
  // Ordenar Vértices por grado de dificultad (Heurística)
  // 1. Mayor Grado (más conflictos)
  // 2. Menor Dominio (menos bloques disponibles por restricciones duras)
  V_ordenados = OrdenarPorHeuristica(V, E)
  
  Asignaciones = MapaVacio() // Clave: Nodo, Valor: BloqueID
  
  SI Backtracking(0, V_ordenados, E, BloquesDisponibles, Asignaciones):
    RETORNAR Asignaciones
  SINO:
    RETORNAR Error("No se encontró solución factible")

FUNCION Backtracking(indice, Nodos, E, Bloques, Asignaciones):
  SI indice == Tamaño(Nodos):
    RETORNAR VERDADERO // Solución completa encontrada
    
  nodo_actual = Nodos[indice]
  
  // Filtrar bloques válidos (Colores)
  // Un bloque es válido si NINGÚN vecino del nodo actual ya tiene ese bloque asignado
  bloques_validos = []
  PARA CADA bloque B en Bloques:
    es_seguro = VERDADERO
    PARA CADA vecino DE nodo_actual EN E:
      SI vecino ESTA EN Asignaciones Y Asignaciones[vecino] == B:
        es_seguro = FALSO
        ROMPER
    SI es_seguro:
      bloques_validos.AGREGAR(B)
      
  // Intentar asignar
  PARA CADA bloque B en bloques_validos:
    Asignaciones[nodo_actual] = B
    
    // Llamada recursiva
    SI Backtracking(indice + 1, Nodos, E, Bloques, Asignaciones):
      RETORNAR VERDADERO
      
    // Si falla, retroceder (Backtrack)
    ELIMINAR Asignaciones[nodo_actual]
    
  RETORNAR FALSO // Ningún color sirvió para este camino
```

---

## 5. Garantía de Restricciones

1.  **Choques de Profesor:** Garantizado por la arista `u.profesor == v.profesor`. El algoritmo de coloreado nunca asigna el mismo color a nodos conectados.
2.  **Choques de Grupo:** Garantizado por la arista `u.grupo == v.grupo`.
3.  **Materias Duplicadas en mismo bloque:** Garantizado si agregamos aristas entre todas las instancias de la misma materia para un grupo.
4.  **Carga Horaria:** Garantizada por la construcción de $V$. Si la materia requiere 5 horas, creamos 5 nodos. Si el algoritmo resuelve el grafo, obligatoriamente se han asignado 5 bloques distintos.

---

## 6. Diseño de Pantallas (UI/UX)

### 6.1. Pantalla de Inicio (Dashboard)
*   **Resumen:** Tarjetas con contadores grandes.
    *   "Total Profesores" (Icono Usuario)
    *   "Total Grupos" (Icono Personas)
    *   "Materias Registradas" (Icono Libro)
*   **Acciones Rápidas:** Botones grandes "Crear Nuevo Horario", "Gestionar Profesores".

### 6.2. Gestión de Profesores (CRUD)
*   **Tabla:** Estilo "Striped" (filas alternas gris claro/blanco).
    *   Columnas: ID, Nombre Completo, Abreviatura, Acciones.
    *   Header: Fondo Verde UPV, texto blanco.
*   **Formulario (Modal o Página):**
    *   Input: Nombre Completo.
    *   Input: Abreviatura (ej. "Ing. G. Cornejo").
    *   Input Number: Máx Horas/Semana.

### 6.3. Gestión de Materias (La más importante)
*   Esta pantalla vincula todo.
*   **Formulario:**
    *   Select: Grupo (ej. "ITI-7-1").
    *   Select: Materia (Nombre).
    *   Select: Profesor (Búsqueda predictiva).
    *   Input Number: Horas por semana.
    *   Select: Tipo Aula (Aula, Lab, Taller).
*   **Lista:** Muestra la carga académica actual agrupada por Grupo.

### 6.4. Visualización de Horarios
*   **Filtros:** Dropdown para seleccionar "Ver por Grupo", "Ver por Profesor", "Ver por Aula".
*   **La Grilla:**
    *   Columnas: Lunes, Martes, Miércoles, Jueves, Viernes.
    *   Filas: Bloques (7:00-8:00, 8:00-9:00, etc.).
    *   Celdas:
        *   Si está ocupado: Rectángulo con color suave (ej. verde pastel).
        *   Texto Principal: Nombre Materia.
        *   Texto Secundario: Profesor / Aula.

---

## 7. Reporte de Carga Horaria

Se generará una vista de tabla simple para validar la equidad laboral.

| Profesor | Lun | Mar | Mie | Jue | Vie | Total Semanal |
| :--- | :---: | :---: | :---: | :---: | :---: | :---: |
| Ing. Pérez | 4 | 2 | 4 | 2 | 0 | **12** |
| Dra. López | 0 | 3 | 3 | 3 | 0 | **9** |

Botón flotante: "Imprimir PDF".
