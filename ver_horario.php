<?php
$page = 'ver';
require_once 'config/db.php';

// Filtros
$tipo_vista = isset($_GET['vista']) ? $_GET['vista'] : 'grupo'; // grupo, profesor
$id_filtro = isset($_GET['id']) ? $_GET['id'] : '';

// Obtener listas para el selector
$grupos = $pdo->query("SELECT * FROM grupos ORDER BY nombre_grupo")->fetchAll();
$profesores = $pdo->query("SELECT * FROM profesores ORDER BY nombre")->fetchAll();

// Lógica de visualización
$horario_data = [];
$bloques_dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes'];
$bloques_horas = [];

// Obtener todos los bloques únicos para las filas
$stmt = $pdo->query("SELECT DISTINCT hora_inicio, hora_fin FROM bloques_horario ORDER BY hora_inicio");
while ($row = $stmt->fetch()) {
    $bloques_horas[] = substr($row['hora_inicio'], 0, 5) . ' - ' . substr($row['hora_fin'], 0, 5);
}

if ($id_filtro) {
    $sql = "SELECT h.*, m.nombre_materia, p.abreviatura as profe, g.nombre_grupo, b.dia, b.hora_inicio, b.hora_fin 
            FROM horarios_generados h
            JOIN materias m ON h.id_materia = m.id_materia
            JOIN profesores p ON m.id_profesor = p.id_profesor
            JOIN grupos g ON m.id_grupo = g.id_grupo
            JOIN bloques_horario b ON h.id_bloque = b.id_bloque ";
    
    if ($tipo_vista == 'grupo') {
        $sql .= "WHERE m.id_grupo = ?";
    } else {
        $sql .= "WHERE m.id_profesor = ?";
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_filtro]);
    $resultados = $stmt->fetchAll();

    // Organizar en matriz [Hora][Dia]
    foreach ($resultados as $r) {
        $hora_str = substr($r['hora_inicio'], 0, 5) . ' - ' . substr($r['hora_fin'], 0, 5);
        $horario_data[$hora_str][$r['dia']] = [
            'materia' => $r['nombre_materia'],
            'profesor' => $r['profe'],
            'grupo' => $r['nombre_grupo']
        ];
    }
}

require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';
?>

<div class="main-content">
    <div class="header">
        <h1>Visualización de Horarios</h1>
        <div><span>Resultados</span></div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Filtros de Búsqueda</h2>
        </div>
        <form method="GET" style="display: flex; gap: 20px; align-items: flex-end;">
            <div class="form-group">
                <label>Ver por:</label>
                <select name="vista" class="form-control" onchange="this.form.submit()">
                    <option value="grupo" <?php echo $tipo_vista == 'grupo' ? 'selected' : ''; ?>>Grupo</option>
                    <option value="profesor" <?php echo $tipo_vista == 'profesor' ? 'selected' : ''; ?>>Profesor</option>
                </select>
            </div>
            <div class="form-group" style="flex: 1;">
                <label>Seleccionar:</label>
                <select name="id" class="form-control">
                    <option value="">-- Seleccione --</option>
                    <?php if ($tipo_vista == 'grupo'): ?>
                        <?php foreach ($grupos as $g): ?>
                            <option value="<?php echo $g['id_grupo']; ?>" <?php echo $id_filtro == $g['id_grupo'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($g['nombre_grupo']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php foreach ($profesores as $p): ?>
                            <option value="<?php echo $p['id_profesor']; ?>" <?php echo $id_filtro == $p['id_profesor'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($p['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>
    </div>

    <?php if ($id_filtro): ?>
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Horario Semanal</h2>
            <button onclick="window.print()" class="btn btn-sm btn-primary"><i class="fas fa-print"></i> Imprimir</button>
        </div>
        <div class="table-container">
            <table class="table" style="text-align: center;">
                <thead>
                    <tr>
                        <th style="width: 15%;">Horario</th>
                        <?php foreach ($bloques_dias as $dia): ?>
                            <th style="width: 17%;"><?php echo $dia; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bloques_horas)): ?>
                        <tr><td colspan="6">No hay bloques de horario definidos.</td></tr>
                    <?php else: ?>
                        <?php foreach ($bloques_horas as $hora): ?>
                        <tr>
                            <td style="font-weight: bold; background: #f9f9f9;"><?php echo $hora; ?></td>
                            <?php foreach ($bloques_dias as $dia): ?>
                                <?php 
                                    $celda = isset($horario_data[$hora][$dia]) ? $horario_data[$hora][$dia] : null;
                                ?>
                                <td style="<?php echo $celda ? 'background-color: #e8f5e9; border: 1px solid #c8e6c9;' : ''; ?>">
                                    <?php if ($celda): ?>
                                        <div style="font-weight: bold; color: var(--upv-green);"><?php echo htmlspecialchars($celda['materia']); ?></div>
                                        <div style="font-size: 0.85rem; color: #666;">
                                            <?php echo ($tipo_vista == 'grupo') ? htmlspecialchars($celda['profesor']) : htmlspecialchars($celda['grupo']); ?>
                                        </div>
                                    <?php else: ?>
                                        <span style="color: #ccc;">-</span>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layout/footer.php'; ?>
