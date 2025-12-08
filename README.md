# Manual de Usuario
## Sistema de Generación de Horarios Universitarios

---

### **Información del Proyecto**

**Institución:** Universidad  Politecnica de Victoria  
**Materia:** Estructuras de Datos

**Integrantes del Equipo:**
- Fernando Guadalupe Flores Flores
- Rodrigo Damián Álvarez Aguilar
- Israel Eliseo Cisneros Salas
- Jose Guadalupe Cornejo Alva

**Versión:** 1.0  
**Fecha:** Diciembre 2024

---

## Tabla de Contenidos

1. [Introducción](#introducción)
2. [Requisitos del Sistema](#requisitos-del-sistema)
3. [Instalación](#instalación)
4. [Inicio de la Aplicación](#inicio-de-la-aplicación)
5. [Interfaz Principal](#interfaz-principal)
6. [Módulo Dashboard](#módulo-dashboard)
7. [Módulo de Datos](#módulo-de-datos)
8. [Módulo de Horarios](#módulo-de-horarios)
9. [Resolución de Problemas](#resolución-de-problemas)
10. [Preguntas Frecuentes](#preguntas-frecuentes)

---

## 1. Introducción

### ¿Qué es el Sistema de Generación de Horarios Universitarios?

El Sistema de Generación de Horarios Universitarios es una aplicación web desarrollada con tecnologías modernas que permite la **generación automática de horarios académicos** optimizados, considerando múltiples restricciones como:

- Disponibilidad de profesores
- Capacidad de grupos
- Distribución de materias por cuatrimestre
- Asignación de salones virtuales
- Evitación de conflictos de horarios

### Características Principales

✅ **Gestión de Profesores:** Registro completo de profesores con su disponibilidad horaria  
✅ **Gestión de Materias:** Administración de cursos por cuatrimestre  
✅ **Gestión de Estudiantes:** Control de alumnos por cuatrimestre  
✅ **Generación Automática:** Algoritmo inteligente para crear horarios óptimos  
✅ **Visualización Múltiple:** Vista de horarios, matriz de compatibilidad y grafo de conflictos  
✅ **Exportación:** Descarga de horarios en formato Excel y PDF  
✅ **Datos Precargados:** Sistema incluye datos de ejemplo para comenzar rápidamente

---

## 2. Requisitos del Sistema

### Requisitos Mínimos de Hardware

- **Procesador:** Intel Core i3 o equivalente
- **Memoria RAM:** 4 GB mínimo (8 GB recomendado)
- **Espacio en Disco:** 500 MB libres
- **Conexión a Internet:** Para instalación de dependencias

### Requisitos de Software

- **Sistema Operativo:** Windows 10/11, Linux (Ubuntu 20.04+), macOS 10.15+
- **Node.js:** Versión 16 o superior
- **Navegador Web:** Google Chrome, Firefox, Edge o Safari (última versión)
- **Editor de Texto** (opcional): VS Code, Sublime Text, etc.

### Verificar Node.js Instalado

Abra una terminal o símbolo del sistema y ejecute:

```bash
node --version
npm --version
```

Debería ver las versiones instaladas (ejemplo: v18.17.0 y 9.8.1).

Si no tiene Node.js instalado, descárguelo desde: https://nodejs.org/

---

## 3. Instalación

### Paso 1: Descargar el Proyecto

Si tiene acceso al repositorio Git:

```bash
git checkout Rodrigo
```

### Paso 2: Instalar Dependencias del Frontend

1. Abra una terminal en la carpeta raíz del proyecto
2. Navegue a la carpeta frontend:

```bash
cd frontend
```

3. Instale las dependencias:

```bash
npm install
```

Este proceso descargará todas las librerías necesarias:
- React 19.2.0 (Framework principal)
- React Router (Navegación)
- Tailwind CSS (Estilos)
- Lucide React (Iconos)
- Recharts (Gráficas)
- jsPDF (Exportación PDF)
- XLSX (Exportación Excel)
- SweetAlert2 (Alertas)
- React Force Graph (Visualización de grafos)

### Paso 3: Compilar el Backend (C++)

El sistema incluye un solver en C++ para la optimización de horarios.

**En Windows:**
```bash
cd ../backend
g++ solver.cpp -o solver.exe
```

**En Linux/Mac:**
```bash
cd ../backend
g++ solver.cpp -o solver
chmod +x solver
```

---

## 4. Inicio de la Aplicación

### Opción 1: Script Automatizado

El proyecto incluye scripts para facilitar el inicio:

**En Windows:**
```bash
run_project.bat
```

**En Linux/Mac:**
```bash
chmod +x run_project.sh
./run_project.sh
```

### Opción 2: Inicio Manual

Abra una terminal en la carpeta `frontend` y ejecute:

```bash
npm run dev
```

Verá un mensaje similar a:

```
  VITE v7.2.4  ready in 450 ms

  ➜  Local:   http://localhost:5173/
  ➜  Network: use --host to expose
  ➜  press h + enter to show help
```

### Acceder a la Aplicación

Abra su navegador web y vaya a:

```
http://localhost:5173
```

La aplicación se cargará y mostrará la pantalla principal.

---

## 5. Interfaz Principal

### Estructura General

La aplicación tiene un diseño de dos columnas:

```
┌─────────────┬──────────────────────────────┐
│             │  Header (Usuario: Admin)     │
│  Sidebar    ├──────────────────────────────┤
│             │                              │
│  - Dashboard│     Contenido Principal      │
│  - Datos    │                              │
│  - Horarios │                              │
│             │                              │
└─────────────┴──────────────────────────────┘
```

### Barra Lateral (Sidebar)

Navegación principal con tres secciones:

🏠 **Dashboard** - Vista general con estadísticas  
📊 **Datos** - Gestión de profesores, materias y alumnos  
📅 **Horarios** - Generación y visualización de horarios

### Header

- **Título:** Sistema de Horarios
- **Usuario:** Muestra "Admin" con icono de usuario
- **Indicador:** Sección actual resaltada

---

## 6. Módulo Dashboard

### Descripción

El Dashboard proporciona una **vista general** del sistema con estadísticas y gráficas en tiempo real.

### Estadísticas Principales

Al entrar al Dashboard verá tres tarjetas principales:

####  **Profesores**
- Muestra el número total de profesores registrados
- Color: Azul
- Incluye profesores predefinidos del sistema

#### **Materias**
- Total de materias/cursos en el sistema
- Color: Verde
- Distribuidas por los 10 cuatrimestres

####  **Alumnos**
- Suma total de estudiantes registrados
- Color: Morado
- Contabiliza todos los cuatrimestres

### Gráficas

#### Gráfica de Barras: Alumnos por Cuatrimestre
- **Eje X:** Cuatrimestres (1° C hasta 10° C)
- **Eje Y:** Cantidad de alumnos
- **Color:** Azul
- **Función:** Visualizar la distribución de estudiantes

#### Gráfica Circular: Materias por Cuatrimestre
- **Tipo:** Pie chart con colores variados
- **Datos:** Porcentaje de materias por cuatrimestre
- **Leyenda:** Nombre y cantidad de materias
- **Función:** Identificar carga académica por nivel

### Interpretación de Datos

**Ejemplo de lectura:**
```
Profesores: 12
Materias: 48
Alumnos: 350

Gráfica de barras muestra:
- 1° Cuatrimestre: 40 alumnos
- 2° Cuatrimestre: 35 alumnos
- etc.
```

Esto indica que hay 12 profesores para impartir 48 materias a 350 estudiantes distribuidos en diferentes niveles.

---

## 7. Módulo de Datos

El módulo de Datos es el **corazón administrativo** del sistema. Aquí se gestionan todos los recursos necesarios para generar horarios.

### 7.1 Pestaña: Profesores

#### Visualización

Tabla con columnas:
- **ID:** Identificador único automático
- **Nombre:** Nombre completo del profesor
- **Materias Asignadas:** Lista de cursos que imparte
- **Acciones:** Botones para editar/eliminar/configurar disponibilidad

#### Profesores Predefinidos

El sistema incluye 12 profesores de ejemplo:

1. José Guadalupe Cornejo Alva
2. José Esaú Pérez Contreras
3. Elisa Maribel Alvarez Gutiérrez
4. Iris Stephanie Herrera De La Mora
5. Luis Arturo Ortega Muñoz
6. José Pablo Nuño Ayala
7. José Martin Franco Salazar
8. Sergio Alberto Macías Macías
9. José Eduardo Solís Hernández
10. Alfredo Lara Hernández
11. José David Estrada
12. Azael Díaz Carrillo

Cada profesor viene con **disponibilidad horaria precargada** y **materias asignadas**.

#### Agregar Nuevo Profesor

**Paso a paso:**

1. Clic en botón **"+ Agregar Profesor"** (esquina superior derecha)
2. Se abre modal con formulario:

   ```
   ┌────────────────────────────────┐
   │  Agregar Profesor              │
   ├────────────────────────────────┤
   │  Nombre: [_______________]     │
   │                                │
   │  Materias Asignadas:           │
   │  [Ingresar materia...] [+]     │
   │                                │
   │  • Matemáticas          [×]    │
   │  • Física               [×]    │
   │                                │
   │  [Cancelar]    [Guardar]       │
   └────────────────────────────────┘
   ```

3. **Ingresar datos:**
   - **Nombre:** Escriba el nombre completo
   - **Materias:** 
     - Escriba el nombre de una materia en el campo
     - Presione **Enter** o clic en **"+"** para agregar
     - Aparecerá en la lista debajo
     - Puede eliminar con el botón **×**

4. Clic en **"Guardar"**

5. Sistema valida:
   - ✅ Nombre no vacío
   - ✅ Nombre único (no duplicado)

6. Confirmación con SweetAlert2

**Ejemplo:**
```
Nombre: María López García
Materias: 
  - Cálculo Diferencial
  - Álgebra Lineal
```

#### Editar Profesor

1. Clic en icono de **lápiz** (Edit2) en la fila del profesor
2. Se abre modal similar al de agregar
3. **Datos precargados** del profesor seleccionado
4. Modificar información necesaria
5. Clic en **"Actualizar"**

**Nota:** El ID no se puede modificar (se asigna automáticamente).

#### Eliminar Profesor

1. Clic en icono de **basura** (Trash2)
2. Confirmación con SweetAlert2:
   ```
   ¿Estás seguro?
   Se eliminará al profesor [Nombre]
   [Cancelar] [Eliminar]
   ```
3. Si confirma, el profesor se elimina permanentemente

**⚠️ Advertencia:** Eliminar un profesor puede afectar materias asignadas.

#### Configurar Disponibilidad Horaria

La disponibilidad es **crucial** para la generación de horarios.

**Pasos:**

1. Clic en botón **"Disponibilidad"** del profesor
2. Se abre componente `AvailabilityGrid`:

   ```
   Disponibilidad Horaria - [Nombre del Profesor]
   
           Lunes  Martes  Miércoles  Jueves  Viernes
   07:00   [ ]    [ ]     [ ]        [ ]     [ ]
   08:00   [ ]    [ ]     [ ]        [ ]     [ ]
   09:00   [✓]    [✓]     [✓]        [✓]     [✓]
   10:00   [✓]    [✓]     [✓]        [✓]     [✓]
   11:00   [✓]    [✓]     [✓]        [✓]     [✓]
   ...
   ```

3. **Interacción:**
   - Clic en casilla vacía **→** Marca disponible (verde)
   - Clic en casilla marcada **→** Desmarca (gris)
   - Horarios: 7:00 AM a 9:00 PM (cada hora)
   - Días: Lunes a Viernes

4. Clic en **"Guardar Disponibilidad"**
5. Los datos se almacenan en LocalStorage

**Recomendación:** Marque **bloques continuos** de 2-3 horas para facilitar la asignación de materias.

### 7.2 Pestaña: Materias

#### Visualización

Tabla con columnas:
- **ID:** Identificador único
- **Nombre:** Nombre de la materia
- **Cuatrimestre:** Nivel (1° al 10°)
- **Sesiones Semanales:** Número de clases por semana
- **Profesor Asignado:** Nombre del profesor (si está asignado)
- **Acciones:** Editar/Eliminar

#### Materias Predefinidas

El sistema incluye **48 materias** distribuidas en los 10 cuatrimestres, por ejemplo:

**1er Cuatrimestre:**
- Matemáticas Básicas (3 sesiones)
- Inglés I (2 sesiones)
- Metodología de la Investigación (2 sesiones)
- Integradora I (2 sesiones)

**2do Cuatrimestre:**
- Cálculo Diferencial (3 sesiones)
- Inglés II (2 sesiones)
- Introducción a la Programación (3 sesiones)
- etc.

#### Agregar Nueva Materia

1. Clic en **"+ Agregar Materia"**
2. Formulario:

   ```
   Nombre: [___________________]
   
   Cuatrimestre: [▼ Seleccionar]
                 1° Cuatrimestre
                 2° Cuatrimestre
                 ...
                 10° Cuatrimestre
   
   Sesiones Semanales: [___]
   
   Profesor: [▼ Seleccionar Profesor]
             - Sin asignar -
             José Guadalupe Cornejo Alva
             José Esaú Pérez Contreras
             ...
   
   [Cancelar] [Guardar]
   ```

3. **Campos:**
   - **Nombre:** Obligatorio
   - **Cuatrimestre:** Seleccionar del 1 al 10
   - **Sesiones Semanales:** Número de clases (típicamente 2-4)
   - **Profesor:** Opcional, puede asignarse después

4. Validaciones:
   - Nombre único
   - Sesiones > 0
   - Cuatrimestre válido

5. Guardar

**Ejemplo:**
```
Nombre: Estructura de Datos
Cuatrimestre: 4° Cuatrimestre
Sesiones Semanales: 3
Profesor: José Guadalupe Cornejo Alva
```

#### Editar Materia

- Similar a agregar, con datos precargados
- Se puede cambiar profesor asignado
- ID no editable

#### Eliminar Materia

- Confirmación requerida
- Se elimina de la base de datos local

### 7.3 Pestaña: Alumnos

#### Descripción

Esta pestaña permite configurar la **cantidad de alumnos por cuatrimestre**. Es fundamental para:
- Calcular grupos necesarios
- Asignar salones virtuales
- Distribuir materias

#### Interfaz

```
Alumnos por Cuatrimestre

┌──────────────────────────────────┐
│  1° Cuatrimestre    [  40  ] ←→  │
│  2° Cuatrimestre    [  35  ] ←→  │
│  3° Cuatrimestre    [  42  ] ←→  │
│  4° Cuatrimestre    [  38  ] ←→  │
│  5° Cuatrimestre    [  30  ] ←→  │
│  6° Cuatrimestre    [  28  ] ←→  │
│  7° Cuatrimestre    [  25  ] ←→  │
│  8° Cuatrimestre    [  22  ] ←→  │
│  9° Cuatrimestre    [  18  ] ←→  │
│  10° Cuatrimestre   [  15  ] ←→  │
└──────────────────────────────────┘

Total: 293 alumnos
```

#### Configuración

1. **Ingresar cantidad** en cada campo numérico
2. Puede usar:
   - Flechas del teclado ↑↓
   - Escribir directamente
   - Incrementar/decrementar con controles

3. **Cálculo automático de grupos:**
   - Capacidad por grupo: **35 alumnos**
   - Fórmula: `Grupos = ⌈Alumnos / 35⌉`

**Ejemplos:**
```
40 alumnos → 2 grupos (40/35 = 1.14 → redondea a 2)
35 alumnos → 1 grupo
70 alumnos → 2 grupos
71 alumnos → 3 grupos
```

4. **Grupos virtuales generados:**
   - Grupo ID: `[Cuatrimestre][Número]`
   - Ejemplo: 
     - 1° Cuatrimestre con 40 alumnos → Grupos: 101, 102
     - 2° Cuatrimestre con 35 alumnos → Grupo: 201

5. Los cambios se **guardan automáticamente** en LocalStorage

#### Datos Guardados

La información se almacena como:
```json
{
  "1": 40,
  "2": 35,
  "3": 42,
  ...
}
```

---

## 8. Módulo de Horarios

El módulo más importante del sistema: **generación y visualización de horarios**.

### 8.1 Vista Principal

#### Elementos de la Interfaz

```
┌────────────────────────────────────────────┐
│  [▶ Generar Horarios]  [↓ Descargar]       │
│                                             │
│  Vista: ● Horario  ○ Matriz  ○ Grafo       │
│                                             │
│  Grupo: [▼ 1-1 ]                           │
│                                             │
│  ┌──────────────────────────────────────┐  │
│  │     HORARIO DEL GRUPO 1-1            │  │
│  │                                       │  │
│  │   Hora │ Lun │ Mar │ Mié │ Jue │ Vie│  │
│  │  ─────┼─────┼─────┼─────┼─────┼────│  │
│  │  07:00│     │     │     │     │    │  │
│  │  08:00│ Mat │     │     │ Mat │    │  │
│  │  09:00│ Mat │ Ing │     │ Mat │Ing │  │
│  │   ...                                │  │
│  └──────────────────────────────────────┘  │
└────────────────────────────────────────────┘
```

### 8.2 Generación de Horarios

#### Proceso Completo

**Paso 1: Preparación**

Antes de generar, asegúrese de tener:
- ✅ Profesores con disponibilidad configurada
- ✅ Materias creadas y asignadas a profesores
- ✅ Alumnos registrados por cuatrimestre

**Paso 2: Iniciar Generación**

1. Clic en botón **"▶ Generar Horarios"**

2. Sistema muestra indicador de carga:
   ```
   ⟳ Generando horarios...
   ```

3. **Proceso interno:**

   a. **Validación:**
      - Verifica que existen alumnos
      - Comprueba materias disponibles
      - Valida profesores con disponibilidad

   b. **Generación de Grupos Virtuales:**
      ```
      Para cada cuatrimestre:
        Calcular número de grupos = ⌈alumnos / 35⌉
        Crear grupos virtuales (ejemplo: 1-1, 1-2)
        Expandir materias para cada grupo
      ```

   c. **Preparación de Datos:**
      - Convierte información a formato de entrada del solver
      - Crea archivo `input.txt` con:
        - Lista de cursos expandidos
        - Restricciones de profesores
        - Disponibilidad horaria

   d. **Ejecución del Solver C++:**
      - Llama a `backend/solver.exe` (Windows) o `backend/solver` (Linux)
      - Algoritmo de backtracking con optimizaciones
      - Busca asignación óptima sin conflictos

   e. **Procesamiento de Resultados:**
      - Lee salida del solver
      - Parsea horarios generados
      - Estructura datos para visualización

   f. **Almacenamiento:**
      - Guarda en LocalStorage
      - Formato JSON con toda la información del horario

**Paso 3: Resultado**

Si es exitoso:
```
✅ ¡Horario generado exitosamente!
```

Si hay error:
```
❌ Error al generar horarios
[Mensaje descriptivo del problema]
```

**Posibles errores:**
- No hay alumnos registrados
- Falta disponibilidad de profesores
- No se encontró solución factible
- Conflictos irresolubles

#### Algoritmo del Solver

El solver implementa:

1. **Búsqueda con Backtracking**
2. **Heurísticas de Ordenamiento:**
   - Cursos con menos opciones primero (Most Constrained Variable)
   - Horarios con menor impacto (Least Constraining Value)

3. **Verificación de Conflictos:**
   - Un profesor no puede estar en dos lugares al mismo tiempo
   - Un grupo no puede tener dos materias simultáneas
   - Respeto de disponibilidad horaria

4. **Optimizaciones:**
   - Poda de rama (branch pruning)
   - Detección temprana de inconsistencias
   - Caché de validaciones

### 8.3 Visualización: Vista de Horario

#### Selector de Grupo

```
Grupo: [▼ 1-1 ]
       1-1
       1-2
       2-1
       3-1
       ...
```

Seleccione un grupo para ver su horario específico.

#### Tabla de Horario

**Estructura:**

```
        Lunes       Martes      Miércoles   Jueves      Viernes
07:00   
08:00   Matemáticas             Matemáticas             Matemáticas
        (Prof. Juan)            (Prof. Juan)            (Prof. Juan)
09:00   
10:00   Inglés I    Inglés I                Inglés I    
        (Prof. Ana) (Prof. Ana)             (Prof. Ana)
11:00
...
```

**Características:**
- **Franjas horarias:** 7:00 AM - 9:00 PM
- **Celdas de materia:** Incluyen nombre y profesor
- **Colores:** Cada materia tiene un color distintivo
- **Vacíos:** Espacios sin clase aparecen en blanco

#### Leyenda de Colores

El sistema asigna colores automáticamente para distinguir materias:
- 🔵 Azul
- 🟢 Verde
- 🟣 Morado
- 🟠 Naranja
- 🔴 Rojo
- 🔵 Cyan
- 🌸 Rosa
- 🟡 Lima
- 🟠 Naranja oscuro
- 🟣 Índigo

### 8.4 Visualización: Matriz de Compatibilidad

Cambie a vista de matriz con el selector:

```
Vista: ○ Horario  ● Matriz  ○ Grafo
```

#### Descripción

La **Matriz de Compatibilidad** muestra:
- Filas y Columnas: Todos los cursos del horario
- Celdas:
  - 🟥 Rojo (valor alto): **Conflicto** - No pueden estar al mismo tiempo
  - 🟨 Amarillo (valor medio): Precaución
  - 🟩 Verde (valor bajo): **Compatible** - Pueden coexistir

#### Interpretación

**Ejemplo:**
```
           Mat-1  Fís-1  Ing-1  Calc-2
Mat-1        0     50     20      10
Fís-1       50      0     30       5
Ing-1       20     30      0      15
Calc-2      10      5     15       0
```

- **Mat-1 vs Fís-1 = 50** (Rojo): Mismo profesor o grupo → Conflicto
- **Mat-1 vs Ing-1 = 20** (Amarillo): Alguna restricción compartida
- **Mat-1 vs Calc-2 = 10** (Verde): Sin conflictos

#### Uso Práctico

- **Identificar materias problemáticas:** Buscar filas/columnas con mucho rojo
- **Validar asignaciones:** Verificar que materias del mismo grupo no tengan conflicto
- **Debugging:** Si la generación falla, revisar matriz para encontrar incompatibilidades

### 8.5 Visualización: Grafo de Conflictos

Cambie a vista de grafo:

```
Vista: ○ Horario  ○ Matriz  ● Grafo
```

#### Descripción

Representación visual tipo **red/network** donde:

- **Nodos:** Cada curso (materia-grupo)
  - Tamaño proporcional a número de sesiones
  - Color según cuatrimestre

- **Enlaces (aristas):** Conflictos entre cursos
  - Línea gruesa = Conflicto fuerte
  - Línea delgada = Conflicto menor

#### Interacción

- **Arrastrar nodos:** Reorganizar el grafo
- **Zoom:** Rueda del mouse
- **Hover:** Ver información del curso
- **Física:** Simulación de fuerzas para distribución automática

#### Interpretación

**Ejemplo visual:**
```
    [Mat-101] ─────┐
        │          │
        │     [Fís-101]
        │          │
    [Ing-101] ─────┘
        │
    [Met-101]
```

Indica:
- Mat-101, Fís-101 e Ing-101 tienen conflictos mutuos (triángulo)
- Met-101 solo conflictúa con Ing-101
- Cuanto más agrupados, más conflictos

#### Uso Práctico

- **Visualización global:** Ver complejidad del problema
- **Clusters:** Identificar grupos de materias relacionadas
- **Densidad:** Muchas líneas = problema complejo
- **Validación:** Tras generar horario, no deberían quedar conflictos críticos

### 8.6 Descarga de Horarios

#### Botón de Descarga

```
[↓ Descargar ▼]
```

Al hacer clic, aparece menú desplegable:

```
📊 Exportar a Excel
📄 Exportar a PDF
```

#### Exportar a Excel

**Proceso:**

1. Seleccionar **"Exportar a Excel"**
2. Sistema genera archivo `.xlsx`
3. Descarga automática: `horarios.xlsx`

**Contenido del archivo:**

- **Hoja 1:** Horario General
  ```
  Grupo | Día      | Hora  | Materia       | Profesor
  1-1   | Lunes    | 08:00 | Matemáticas   | Juan Pérez
  1-1   | Lunes    | 10:00 | Inglés I      | Ana García
  ...
  ```

- **Hojas adicionales:** Una hoja por grupo
  - Tabla estructurada como vista web
  - Formato de cuadrícula
  - Fácil de imprimir

**Características:**
- Compatible con Excel, LibreOffice, Google Sheets
- Formato estándar XLSX
- Incluye estilos básicos

#### Exportar a PDF

**Proceso:**

1. Seleccionar **"Exportar a PDF"**
2. Sistema genera archivo PDF
3. Descarga automática: `horarios.pdf`

**Contenido del archivo:**

- **Página 1:** Portada
  ```
  HORARIOS ACADÉMICOS
  [Fecha de generación]
  ```

- **Páginas siguientes:** Un horario por grupo
  - Tabla detallada
  - Encabezado con nombre del grupo
  - Formato profesional

**Características:**
- Listo para imprimir
- Formato A4
- Incluye bordes y estilos
- Biblioteca: jsPDF + autoTable

#### Usos Recomendados

- **Excel:** Para análisis adicional, modificaciones, reportes
- **PDF:** Para distribución oficial, impresión, archivo

---

## 9. Resolución de Problemas

### Problema 1: No se puede instalar dependencias

**Síntoma:**
```
npm install
Error: EACCES permission denied
```

**Solución:**
- Linux/Mac: No use sudo, configure npm correctamente
  ```bash
  npm config set prefix ~/.npm-global
  export PATH=~/.npm-global/bin:$PATH
  ```
- Windows: Ejecute terminal como Administrador

### Problema 2: Aplicación no inicia

**Síntoma:**
```
npm run dev
Error: Cannot find module 'vite'
```

**Solución:**
1. Eliminar node_modules y reinstalar:
   ```bash
   rm -rf node_modules package-lock.json
   npm install
   ```
2. Verificar versión de Node.js (debe ser 16+)

### Problema 3: No se generan horarios

**Síntoma:**
- Botón "Generar Horarios" no responde
- Mensaje: "No hay alumnos registrados"

**Solución:**
1. Ir a **Datos → Alumnos**
2. Verificar que al menos un cuatrimestre tiene alumnos > 0
3. Guardar cambios
4. Intentar nuevamente

### Problema 4: Error del solver

**Síntoma:**
```
Error al generar horarios
No se pudo ejecutar el solver
```

**Solución Windows:**
```bash
cd backend
g++ solver.cpp -o solver.exe
```

**Solución Linux/Mac:**
```bash
cd backend
g++ solver.cpp -o solver
chmod +x solver
```

### Problema 5: Horarios con conflictos

**Síntoma:**
- Un profesor aparece en dos lugares al mismo tiempo
- Grupos traslapados

**Solución:**
1. Revisar **disponibilidad de profesores**
2. Verificar que cada profesor tenga suficientes horas marcadas
3. Comprobar asignaciones de materias
4. Regenerar horarios

### Problema 6: Página en blanco

**Síntoma:**
- Navegador muestra pantalla blanca
- Consola del navegador tiene errores

**Solución:**
1. Abrir consola del navegador (F12)
2. Buscar errores en rojo
3. Limpiar LocalStorage:
   ```javascript
   localStorage.clear()
   location.reload()
   ```
4. Recargar página

### Problema 7: Datos no se guardan

**Síntoma:**
- Agregar profesor/materia parece funcionar
- Al recargar, datos desaparecen

**Solución:**
1. Verificar que LocalStorage esté habilitado en navegador
2. Revisar espacio disponible (límite típico: 5-10 MB)
3. Limpiar datos antiguos si es necesario
4. Probar en modo incógnito para descartar extensiones

---

## 10. Preguntas Frecuentes

### ¿Puedo usar el sistema sin conexión a Internet?

**Sí**, una vez instaladas las dependencias. El sistema funciona completamente offline usando LocalStorage.

### ¿Dónde se guardan los datos?

En el **LocalStorage del navegador**. Los datos persisten entre sesiones pero son específicos del navegador y dominio.

### ¿Cuántos alumnos soporta el sistema?

No hay límite técnico, pero el rendimiento óptimo es hasta **1000 alumnos**. El solver puede tardar más con datasets grandes.

### ¿Puedo tener más de 10 cuatrimestres?

El sistema está diseñado para **10 cuatrimestres**. Para modificar, sería necesario editar el código fuente.

### ¿Qué pasa si borro el LocalStorage?

**Se pierden todos los datos** (profesores, materias, alumnos, horarios). Se recomienda exportar regularmente.

### ¿Puedo importar datos desde Excel?

Actualmente **no**. La importación no está implementada. Solo exportación.

### ¿El solver siempre encuentra solución?

**No garantizado**. Si las restricciones son muy estrictas (pocas horas disponibles, muchas materias), puede no encontrar solución válida.

**Recomendación:** 
- Asegurar disponibilidad amplia de profesores
- Distribuir materias equilibradamente
- Verificar que grupos tengan suficientes franjas horarias

### ¿Cómo interpreto "No se encontró solución factible"?

Significa que el algoritmo probó todas las combinaciones posibles sin éxito. **Causas comunes:**

1. **Profesor sobrecargado:** Más materias asignadas que horas disponibles
2. **Conflictos de disponibilidad:** Dos materias del mismo grupo requieren mismo horario
3. **Grupos muy grandes:** Necesitan muchas horas que no están disponibles

**Solución:**
- Agregar más profesores
- Ampliar disponibilidad horaria
- Reducir sesiones semanales de algunas materias
- Redistribuir materias entre profesores

### ¿Puedo modificar un horario generado manualmente?

**No directamente en la interfaz**. Una vez generado, el horario se muestra solo para visualización/descarga.

Para modificar:
1. Ajustar datos (disponibilidad, asignaciones)
2. Regenerar horario

### ¿Qué navegador funciona mejor?

Todos los modernos funcionan bien:
- ✅ **Google Chrome** (Recomendado)
- ✅ Firefox
- ✅ Edge
- ✅ Safari

Evite Internet Explorer (obsoleto).

### ¿Cómo reinicio el sistema a datos predefinidos?

**Método 1: Desde consola del navegador (F12)**
```javascript
localStorage.clear()
location.reload()
```

**Método 2: Función en código**
Agregar botón "Resetear Datos" que llame a:
```javascript
storage.resetToDefault()
```

### ¿Puedo usar el sistema para una escuela real?

**Sí**, pero considere:
- Validar resultados antes de publicar
- Hacer pruebas con datos reales
- Tener plan B manual
- Considerar agregar más validaciones según necesidades específicas

### ¿Cómo contribuyo al proyecto?

Si tiene acceso al repositorio:
1. Clone la rama Rodrigo
2. Cree una rama nueva para su feature
3. Haga commits descriptivos
4. Envíe pull request

---

## Apéndice A: Estructura de Datos

### LocalStorage Keys

```javascript
{
  "teachers": [...],      // Array de profesores
  "courses": [...],       // Array de materias
  "students": {...},      // Objeto {cuatrimestre: cantidad}
  "schedule": {...}       // Horario generado
}
```

### Formato de Profesor

```javascript
{
  "id": 1,
  "name": "José Guadalupe Cornejo Alva",
  "assignedCourses": ["Estructuras de Datos", "Programación"],
  "availability": {
    "Lunes": {
      "07:00": false,
      "08:00": true,
      "09:00": true,
      ...
    },
    ...
  }
}
```

### Formato de Materia

```javascript
{
  "id": 1,
  "name": "Matemáticas Básicas",
  "cuatrimestre": "1",
  "weekly_sessions": 3,
  "teacher_id": 1
}
```

### Formato de Horario

```javascript
{
  "101": {  // Group ID
    "Lunes": {
      "08:00": {
        "course": "Matemáticas Básicas",
        "teacher": "José Guadalupe Cornejo Alva"
      }
    }
  }
}
```

---

## Apéndice B: Tecnologías Utilizadas

### Frontend

- **React 19.2.0** - Framework UI
- **React Router DOM 7.10.0** - Navegación
- **Tailwind CSS 3.4.17** - Estilos
- **Vite 7.2.4** - Build tool
- **Lucide React** - Iconos
- **Recharts 3.5.1** - Gráficas
- **SweetAlert2 11.26.3** - Alertas
- **jsPDF 3.0.4** - Generación PDF
- **jsPDF-AutoTable 5.0.2** - Tablas en PDF
- **XLSX 0.18.5** - Exportación Excel
- **React Force Graph 2D 1.29.0** - Visualización de grafos

### Backend

- **C++** - Solver de optimización
- **G++** - Compilador

### Almacenamiento

- **LocalStorage** - Persistencia cliente

---

## Apéndice C: Contacto y Soporte

### Equipo de Desarrollo

**Universidad Tecnológica del Norte de Aguascalientes**

**Integrantes:**
- **Fernando Guadalupe Flores Flores**
- **Rodrigo Damián Álvarez Aguilar**
- **Israel Eliseo Cisneros Salas**
- **Jose Guadalupe Cornejo Alva**

**Materia:** Estructuras de Datos  
**Fecha:** Diciembre 2024

---

## Apéndice D: Glosario

- **Cuatrimestre:** Período académico de 4 meses
- **Sesión:** Clase individual de una materia (generalmente 1-2 horas)
- **Grupo Virtual:** Subdivisión automática de estudiantes de un cuatrimestre
- **Disponibilidad:** Horarios en que un profesor puede impartir clases
- **Conflicto:** Situación donde dos actividades requieren mismo recurso simultáneamente
- **Solver:** Programa que resuelve el problema de optimización
- **Backtracking:** Algoritmo de búsqueda exhaustiva con retroceso
- **LocalStorage:** Almacenamiento persistente en navegador web
- **Matriz de Compatibilidad:** Tabla que muestra conflictos entre pares de cursos
- **Grafo de Conflictos:** Representación visual de relaciones entre cursos

---

## Conclusión

Este manual proporciona una guía completa para usar el **Sistema de Generación de Horarios Universitarios**. Con esta herramienta, la tarea de crear horarios académicos complejos se simplifica significativamente.

**Recomendaciones finales:**
1. Mantenga datos actualizados y consistentes
2. Configure disponibilidad de profesores cuidadosamente
3. Revise horarios generados antes de publicar
4. Exporte regularmente como respaldo
5. Reporte problemas o mejoras al equipo de desarrollo

**¡Gracias por usar nuestro sistema!**

---

*Manual de Usuario v1.0 - Diciembre 2024*  
*Sistema de Generación de Horarios Universitarios*  
*Universidad Tecnológica del Norte de Aguascalientes*
