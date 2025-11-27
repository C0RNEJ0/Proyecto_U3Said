<?php
$page = 'materias';
require_once 'config/db.php';

$message = '';

// Handle Create/Delete
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'create') {
        $nombre = $_POST['nombre_materia'];
        $grupo = $_POST['id_grupo'];
        $profesor = $_POST['id_profesor'];
        $horas = $_POST['horas_semana'];
        $tipo = $_POST['tipo_aula_requerida'];

        try {
            $stmt = $pdo->prepare("INSERT INTO materias (nombre_materia, id_grupo, id_profesor, horas_semana, tipo_aula_requerida) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $grupo, $profesor, $horas, $tipo]);
            $message = '<div style="color: green; margin-bottom: 10px;">Materia asignada correctamente.</div>';
        } catch (PDOException $e) {
            $message = '<div style="color: red; margin-bottom: 10px;">Error: ' . $e->getMessage() . '</div>';
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id = $_POST['id_materia'];
        try {
            $stmt = $pdo->prepare("DELETE FROM materias WHERE id_materia = ?");
            $stmt->execute([$id]);
            $message = '<div style="color: green; margin-bottom: 10px;">Materia eliminada correctamente.</div>';
        } catch (PDOException $e) {
            $message = '<div style="color: red; margin-bottom: 10px;">Error: ' . $e->getMessage() . '</div>';
        }
    }
}

// Fetch Data for Selects
$profesores = $pdo->query("SELECT * FROM profesores ORDER BY nombre ASC")->fetchAll();
$grupos = $pdo->query("SELECT * FROM grupos ORDER BY nombre_grupo ASC")->fetchAll();

// Fetch Materias List (Joined)
$sql = "SELECT m.*, g.nombre_grupo, p.abreviatura as nombre_profesor 
        FROM materias m 
        JOIN grupos g ON m.id_grupo = g.id_grupo 
        JOIN profesores p ON m.id_profesor = p.id_profesor 
        ORDER BY g.nombre_grupo, m.nombre_materia";
$materias = $pdo->query($sql)->fetchAll();

require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';
?>

<div class="main-content">
    <div class="header">
        <h1>Gestión de Materias (Carga Académica)</h1>
        <div><span>Planificación</span></div>
    </div>

    <?php echo $message; ?>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Asignar Nueva Materia</h2>
        </div>
        <form method="POST" action="">
            <input type="hidden" name="action" value="create">
            <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1; min-width: 200px;">
                    <label>Grupo</label>
                    <select name="id_grupo" class="form-control" required>
                        <option value="">Seleccione Grupo...</option>
                        <?php foreach ($grupos as $g): ?>
                            <option value="<?php echo $g['id_grupo']; ?>"><?php echo htmlspecialchars($g['nombre_grupo']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="flex: 2; min-width: 300px;">
                    <label>Nombre Materia</label>
                    <input type="text" name="nombre_materia" class="form-control" required placeholder="Ej. Cálculo Diferencial">
                </div>
                <div class="form-group" style="flex: 1; min-width: 200px;">
                    <label>Profesor</label>
                    <select name="id_profesor" class="form-control" required>
                        <option value="">Seleccione Profesor...</option>
                        <?php foreach ($profesores as $p): ?>
                            <option value="<?php echo $p['id_profesor']; ?>"><?php echo htmlspecialchars($p['abreviatura']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="flex: 0 0 100px;">
                    <label>Hrs/Sem</label>
                    <input type="number" name="horas_semana" class="form-control" value="4" min="1" max="10">
                </div>
                <div class="form-group" style="flex: 1; min-width: 150px;">
                    <label>Aula Req.</label>
                    <select name="tipo_aula_requerida" class="form-control">
                        <option value="Normal">Normal</option>
                        <option value="Laboratorio">Laboratorio</option>
                        <option value="Taller">Taller</option>
                    </select>
                </div>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary">Guardar Asignación</button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Listado de Materias por Grupo</h2>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Grupo</th>
                        <th>Materia</th>
                        <th>Profesor</th>
                        <th>Hrs/Sem</th>
                        <th>Tipo Aula</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materias as $m): ?>
                    <tr>
                        <td style="font-weight: bold; color: var(--upv-green);"><?php echo htmlspecialchars($m['nombre_grupo']); ?></td>
                        <td><?php echo htmlspecialchars($m['nombre_materia']); ?></td>
                        <td><?php echo htmlspecialchars($m['nombre_profesor']); ?></td>
                        <td><?php echo $m['horas_semana']; ?></td>
                        <td><?php echo $m['tipo_aula_requerida']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar materia?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id_materia" value="<?php echo $m['id_materia']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
