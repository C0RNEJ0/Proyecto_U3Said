import random

class Evento:
    def __init__(self, id_evento, materia_nombre, profesor, grupo):
        self.id = id_evento
        self.materia = materia_nombre
        self.profesor = profesor
        self.grupo = grupo
        self.color_asignado = None  # ID del bloque de horario

    def __repr__(self):
        return f"[{self.grupo}] {self.materia} ({self.profesor})"

class GrafoHorario:
    def __init__(self):
        self.nodos = []
        self.adyacencia = {}  # Diccionario: id_evento -> lista de id_eventos vecinos

    def agregar_nodo(self, evento):
        self.nodos.append(evento)
        self.adyacencia[evento.id] = []

    def agregar_arista(self, id1, id2):
        if id2 not in self.adyacencia[id1]:
            self.adyacencia[id1].append(id2)
        if id1 not in self.adyacencia[id2]:
            self.adyacencia[id2].append(id1)

    def construir_conflictos(self):
        """
        Genera las aristas basadas en las reglas de negocio:
        1. Mismo Profesor
        2. Mismo Grupo
        3. Misma Materia (para distribuir horas)
        """
        n = len(self.nodos)
        for i in range(n):
            for j in range(i + 1, n):
                u = self.nodos[i]
                v = self.nodos[j]
                
                hay_conflicto = False
                
                # Regla 1: Choque de Profesor
                if u.profesor == v.profesor:
                    hay_conflicto = True
                    
                # Regla 2: Choque de Grupo
                if u.grupo == v.grupo:
                    hay_conflicto = True
                
                # Regla 3: Misma materia (no queremos dos horas de la misma materia pegadas o en el mismo slot)
                # Nota: En este modelo simple 'color' es un slot único.
                if u.materia == v.materia and u.grupo == v.grupo:
                    hay_conflicto = True

                if hay_conflicto:
                    self.agregar_arista(u.id, v.id)

    def obtener_grado(self, id_nodo):
        return len(self.adyacencia[id_nodo])

def resolver_backtracking(grafo, colores_disponibles):
    """
    Intenta asignar un color a cada nodo usando Backtracking + Heurística de Grado.
    """
    # Ordenar nodos por grado descendente (Heurística: resolver los más difíciles primero)
    nodos_ordenados = sorted(grafo.nodos, key=lambda x: grafo.obtener_grado(x.id), reverse=True)
    
    return backtracking_recursivo(grafo, nodos_ordenados, 0, colores_disponibles)

def backtracking_recursivo(grafo, nodos, indice, colores):
    # Caso Base: Todos asignados
    if indice == len(nodos):
        return True

    nodo_actual = nodos[indice]
    
    # Probar colores
    for color in colores:
        if es_seguro(grafo, nodo_actual, color):
            nodo_actual.color_asignado = color
            
            # Recurso
            if backtracking_recursivo(grafo, nodos, indice + 1, colores):
                return True
            
            # Backtrack
            nodo_actual.color_asignado = None
            
    return False

def es_seguro(grafo, nodo, color):
    vecinos_ids = grafo.adyacencia[nodo.id]
    for vecino_id in vecinos_ids:
        # Buscar el objeto nodo vecino (ineficiente pero claro para el ejemplo)
        vecino = next((n for n in grafo.nodos if n.id == vecino_id), None)
        if vecino and vecino.color_asignado == color:
            return False
    return True

# --- EJECUCIÓN DE PRUEBA ---

def main():
    print("--- Iniciando Generación de Horarios (Prototipo) ---")
    
    # 1. Definir Datos de Prueba
    profesores = ["Prof. A", "Prof. B"]
    grupos = ["G1", "G2"]
    materias_base = [
        {"nombre": "Matemáticas", "prof": "Prof. A", "horas": 3},
        {"nombre": "Historia",    "prof": "Prof. B", "horas": 2},
        {"nombre": "Física",      "prof": "Prof. A", "horas": 2}, # Prof A da dos materias
        {"nombre": "Inglés",      "prof": "Prof. B", "horas": 3}
    ]
    
    # 2. Expandir materias en Eventos (Vértices)
    grafo = GrafoHorario()
    contador_id = 0
    
    for g in grupos:
        for m in materias_base:
            # Asignamos materias a grupos (simplificado: todos los grupos llevan todo)
            # En un caso real, esto vendría de la tabla 'materias' filtrada por grupo
            
            # Solo asignamos si tiene sentido (ej. Prof A no puede dar clase a G1 y G2 al mismo tiempo,
            # el grafo lo resolverá, pero aquí creamos la demanda).
            
            for h in range(m["horas"]):
                evento = Evento(contador_id, m["nombre"], m["prof"], g)
                grafo.agregar_nodo(evento)
                contador_id += 1
                
    print(f"Total de eventos (nodos) a programar: {len(grafo.nodos)}")
    
    # 3. Construir Grafo (Aristas)
    grafo.construir_conflictos()
    
    # 4. Definir Colores (Bloques de Horario)
    # Supongamos 5 bloques por día, 2 días = 10 bloques disponibles
    colores = [f"Bloque {i+1}" for i in range(15)] 
    
    # 5. Resolver
    exito = resolver_backtracking(grafo, colores)
    
    if exito:
        print("\n¡Horario Generado con Éxito!\n")
        # Mostrar resultados agrupados por Grupo
        for g in grupos:
            print(f"--- Horario Grupo {g} ---")
            eventos_g = [n for n in grafo.nodos if n.grupo == g]
            eventos_g.sort(key=lambda x: int(x.color_asignado.split()[1]))
            for e in eventos_g:
                print(f"{e.color_asignado}: {e.materia} ({e.profesor})")
            print("")
    else:
        print("No se encontró solución. Intenta aumentar los bloques disponibles.")

if __name__ == "__main__":
    main()
