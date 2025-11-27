<?php
$page = 'profesores';
require_once 'config/db.php';

$message = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'create') {
        $nombre = $_POST['nombre'];
        $abreviatura = $_POST['abreviatura'];
        $max_horas = $_POST['max_horas_semana'];

        try {
            $stmt = $pdo->prepare("INSERT INTO profesores (nombre, abreviatura, max_horas_semana) VALUES (?, ?, ?)");
            $stmt->execute([$nombre, $abreviatura, $max_horas]);
            $message = '<div style="color: green; margin-bottom: 10px;">Profesor agregado correctamente.</div>';
        } catch (PDOException $e) {
            $message = '<div style="color: red; margin-bottom: 10px;">Error al agregar: ' . $e->getMessage() . '</div>';
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id = $_POST['id_profesor'];
        try {
            $stmt = $pdo->prepare("DELETE FROM profesores WHERE id_profesor = ?");
            $stmt->execute([$id]);
            $message = '<div style="color: green; margin-bottom: 10px;">Profesor eliminado correctamente.</div>';
        } catch (PDOException $e) {
            $message = '<div style="color: red; margin-bottom: 10px;">Error al eliminar: ' . $e->getMessage() . '</div>';
        }
    }
}

// Fetch Professors
$stmt = $pdo->query("SELECT * FROM profesores ORDER BY nombre ASC");
$profesores = $stmt->fetchAll();

require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';
?>

<div class="main-content">
    <div class="header">
        <h1>Gestión de Profesores</h1>
        <div>
            <span>Administración Académica</span>
        </div>
    </div>

    <?php echo $message; ?>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Nuevo Profesor</h2>
        </div>
        <form method="POST" action="">
            <input type="hidden" name="action" value="create">
            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 2;">
                    <label>Nombre Completo</label>
                    <input type="text" name="nombre" class="form-control" required placeholder="Ej. Juan Pérez López">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Abreviatura</label>
                    <input type="text" name="abreviatura" class="form-control" required placeholder="Ej. MTI Pérez">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Máx. Horas/Semana</label>
                    <input type="number" name="max_horas_semana" class="form-control" value="40">
                </div>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary">Guardar Profesor</button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Listado de Profesores</h2>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Abreviatura</th>
                        <th>Máx. Horas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($profesores as $p): ?>
                    <tr>
                        <td><?php echo $p['id_profesor']; ?></td>
                        <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($p['abreviatura']); ?></td>
                        <td><?php echo $p['max_horas_semana']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id_profesor" value="<?php echo $p['id_profesor']; ?>">
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
