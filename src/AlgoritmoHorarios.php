<?php

class Evento {
    public $id;
    public $materia_id;
    public $materia_nombre;
    public $profesor_id;
    public $grupo_id;
    public $color_asignado = null; // id_bloque

    public function __construct($id, $materia_id, $materia_nombre, $profesor_id, $grupo_id) {
        $this->id = $id;
        $this->materia_id = $materia_id;
        $this->materia_nombre = $materia_nombre;
        $this->profesor_id = $profesor_id;
        $this->grupo_id = $grupo_id;
    }
}

class GrafoHorario {
    public $nodos = [];
    public $adyacencia = [];

    public function agregarNodo($evento) {
        $this->nodos[] = $evento;
        $this->adyacencia[$evento->id] = [];
    }

    public function agregarArista($id1, $id2) {
        if (!in_array($id2, $this->adyacencia[$id1])) {
            $this->adyacencia[$id1][] = $id2;
        }
        if (!in_array($id1, $this->adyacencia[$id2])) {
            $this->adyacencia[$id2][] = $id1;
        }
    }

    public function construirConflictos() {
        $n = count($this->nodos);
        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $u = $this->nodos[$i];
                $v = $this->nodos[$j];

                $conflicto = false;
                
                // 1. Mismo Profesor
                if ($u->profesor_id == $v->profesor_id) $conflicto = true;
                
                // 2. Mismo Grupo
                if ($u->grupo_id == $v->grupo_id) $conflicto = true;

                // 3. Misma Materia (Evitar clases dobles en el mismo slot)
                if ($u->materia_id == $v->materia_id) $conflicto = true;

                if ($conflicto) {
                    $this->agregarArista($u->id, $v->id);
                }
            }
        }
    }

    public function obtenerGrado($id) {
        return count($this->adyacencia[$id]);
    }
}

class GeneradorHorarios {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function generar() {
        // 1. Obtener Datos
        $materias = $this->pdo->query("SELECT * FROM materias")->fetchAll(PDO::FETCH_ASSOC);
        $bloques = $this->pdo->query("SELECT id_bloque FROM bloques_horario ORDER BY id_bloque ASC")->fetchAll(PDO::FETCH_COLUMN);

        if (empty($materias) || empty($bloques)) {
            throw new Exception("Faltan datos (materias o bloques) para generar.");
        }

        // 2. Crear Grafo
        $grafo = new GrafoHorario();
        $evento_id = 0;

        foreach ($materias as $m) {
            for ($i = 0; $i < $m['horas_semana']; $i++) {
                $evento = new Evento(
                    $evento_id++, 
                    $m['id_materia'], 
                    $m['nombre_materia'], 
                    $m['id_profesor'], 
                    $m['id_grupo']
                );
                $grafo->agregarNodo($evento);
            }
        }

        $grafo->construirConflictos();

        // 3. Resolver (Backtracking)
        // Ordenar nodos por grado (heurística)
        usort($grafo->nodos, function($a, $b) use ($grafo) {
            return $grafo->obtenerGrado($b->id) - $grafo->obtenerGrado($a->id);
        });

        if ($this->backtracking(0, $grafo->nodos, $grafo, $bloques)) {
            // 4. Guardar Resultados
            $this->pdo->exec("DELETE FROM horarios_generados"); // Limpiar anterior
            $stmt = $this->pdo->prepare("INSERT INTO horarios_generados (id_materia, id_bloque) VALUES (?, ?)");
            
            foreach ($grafo->nodos as $nodo) {
                $stmt->execute([$nodo->materia_id, $nodo->color_asignado]);
            }
            return true;
        } else {
            return false;
        }
    }

    private function backtracking($indice, &$nodos, $grafo, $bloques) {
        if ($indice == count($nodos)) {
            return true;
        }

        $nodo = $nodos[$indice];

        // Probar bloques
        // Mezclar bloques para dar variedad si se desea, o secuencial
        // shuffle($bloques); 

        foreach ($bloques as $bloque_id) {
            if ($this->esSeguro($nodo, $bloque_id, $grafo, $nodos)) {
                $nodo->color_asignado = $bloque_id;

                if ($this->backtracking($indice + 1, $nodos, $grafo, $bloques)) {
                    return true;
                }

                $nodo->color_asignado = null; // Backtrack
            }
        }

        return false;
    }

    private function esSeguro($nodo, $bloque_id, $grafo, $nodos) {
        foreach ($grafo->adyacencia[$nodo->id] as $vecino_id) {
            // Buscar el vecino en el array de nodos (que puede estar desordenado o en otro indice)
            // Optimización: Podríamos tener un mapa id->nodo, pero por ahora búsqueda lineal simple
            $vecino = null;
            foreach ($nodos as $n) {
                if ($n->id == $vecino_id) {
                    $vecino = $n;
                    break;
                }
            }

            if ($vecino && $vecino->color_asignado == $bloque_id) {
                return false;
            }
        }
        return true;
    }
}
?>
