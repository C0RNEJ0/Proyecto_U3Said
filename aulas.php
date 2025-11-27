<?php
$page = 'aulas';
require_once 'config/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'create') {
        $nombre = $_POST['nombre_aula'];
        $capacidad = $_POST['capacidad'];
        $tipo = $_POST['tipo_aula'];

        try {
            $stmt = $pdo->prepare("INSERT INTO aulas (nombre_aula, capacidad, tipo_aula) VALUES (?, ?, ?)");
            $stmt->execute([$nombre, $capacidad, $tipo]);
            $message = '<div style="color: green; margin-bottom: 10px;">Aula agregada correctamente.</div>';
        } catch (PDOException $e) {
            $message = '<div style="color: red; margin-bottom: 10px;">Error: ' . $e->getMessage() . '</div>';
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id = $_POST['id_aula'];
        try {
            $stmt = $pdo->prepare("DELETE FROM aulas WHERE id_aula = ?");
            $stmt->execute([$id]);
            $message = '<div style="color: green; margin-bottom: 10px;">Aula eliminada correctamente.</div>';
        } catch (PDOException $e) {
            $message = '<div style="color: red; margin-bottom: 10px;">Error: ' . $e->getMessage() . '</div>';
        }
    }
}

$stmt = $pdo->query("SELECT * FROM aulas ORDER BY nombre_aula ASC");
$aulas = $stmt->fetchAll();

require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';
?>

<div class="main-content">
    <div class="header">
        <h1>Gestión de Aulas</h1>
        <div><span>Infraestructura</span></div>
    </div>

    <?php echo $message; ?>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Nueva Aula</h2>
        </div>
        <form method="POST" action="">
            <input type="hidden" name="action" value="create">
            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 2;">
                    <label>Nombre Aula</label>
                    <input type="text" name="nombre_aula" class="form-control" required placeholder="Ej. A-101 o Lab Cisco">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Capacidad</label>
                    <input type="number" name="capacidad" class="form-control" value="30">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Tipo</label>
                    <select name="tipo_aula" class="form-control">
                        <option value="Normal">Normal</option>
                        <option value="Laboratorio">Laboratorio</option>
                        <option value="Taller">Taller</option>
                    </select>
                </div>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary">Guardar Aula</button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Listado de Aulas</h2>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Capacidad</th>
                        <th>Tipo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($aulas as $a): ?>
                    <tr>
                        <td><?php echo $a['id_aula']; ?></td>
                        <td><?php echo htmlspecialchars($a['nombre_aula']); ?></td>
                        <td><?php echo $a['capacidad']; ?></td>
                        <td><?php echo $a['tipo_aula']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar aula?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id_aula" value="<?php echo $a['id_aula']; ?>">
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
