<?php
$page = 'grupos';
require_once 'config/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'create') {
        $nombre = $_POST['nombre_grupo'];
        $semestre = $_POST['semestre'];
        $turno = $_POST['turno'];
        $alumnos = $_POST['num_alumnos'];

        try {
            $stmt = $pdo->prepare("INSERT INTO grupos (nombre_grupo, semestre, turno, num_alumnos) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nombre, $semestre, $turno, $alumnos]);
            $message = '<div style="color: green; margin-bottom: 10px;">Grupo agregado correctamente.</div>';
        } catch (PDOException $e) {
            $message = '<div style="color: red; margin-bottom: 10px;">Error: ' . $e->getMessage() . '</div>';
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id = $_POST['id_grupo'];
        try {
            $stmt = $pdo->prepare("DELETE FROM grupos WHERE id_grupo = ?");
            $stmt->execute([$id]);
            $message = '<div style="color: green; margin-bottom: 10px;">Grupo eliminado correctamente.</div>';
        } catch (PDOException $e) {
            $message = '<div style="color: red; margin-bottom: 10px;">Error: ' . $e->getMessage() . '</div>';
        }
    }
}

$stmt = $pdo->query("SELECT * FROM grupos ORDER BY semestre ASC, nombre_grupo ASC");
$grupos = $stmt->fetchAll();

require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';
?>

<div class="main-content">
    <div class="header">
        <h1>Gestión de Grupos</h1>
        <div><span>Administración Académica</span></div>
    </div>

    <?php echo $message; ?>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Nuevo Grupo</h2>
        </div>
        <form method="POST" action="">
            <input type="hidden" name="action" value="create">
            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Nombre Grupo</label>
                    <input type="text" name="nombre_grupo" class="form-control" required placeholder="Ej. ITI-1-1">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Semestre</label>
                    <input type="number" name="semestre" class="form-control" required min="1" max="12">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Turno</label>
                    <select name="turno" class="form-control">
                        <option value="Matutino">Matutino</option>
                        <option value="Vespertino">Vespertino</option>
                        <option value="Mixto">Mixto</option>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Num. Alumnos</label>
                    <input type="number" name="num_alumnos" class="form-control" value="30">
                </div>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary">Guardar Grupo</button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Listado de Grupos</h2>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Semestre</th>
                        <th>Turno</th>
                        <th>Alumnos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($grupos as $g): ?>
                    <tr>
                        <td><?php echo $g['id_grupo']; ?></td>
                        <td><?php echo htmlspecialchars($g['nombre_grupo']); ?></td>
                        <td><?php echo $g['semestre']; ?></td>
                        <td><?php echo $g['turno']; ?></td>
                        <td><?php echo $g['num_alumnos']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar grupo?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id_grupo" value="<?php echo $g['id_grupo']; ?>">
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
