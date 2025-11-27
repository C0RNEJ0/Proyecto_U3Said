<?php
$page = 'bloques';
require_once 'config/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'create') {
        $dia = $_POST['dia'];
        $inicio = $_POST['hora_inicio'];
        $fin = $_POST['hora_fin'];
        $turno = $_POST['turno'];

        try {
            $stmt = $pdo->prepare("INSERT INTO bloques_horario (dia, hora_inicio, hora_fin, turno) VALUES (?, ?, ?, ?)");
            $stmt->execute([$dia, $inicio, $fin, $turno]);
            $message = '<div style="color: green; margin-bottom: 10px;">Bloque agregado correctamente.</div>';
        } catch (PDOException $e) {
            $message = '<div style="color: red; margin-bottom: 10px;">Error: ' . $e->getMessage() . '</div>';
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id = $_POST['id_bloque'];
        try {
            $stmt = $pdo->prepare("DELETE FROM bloques_horario WHERE id_bloque = ?");
            $stmt->execute([$id]);
            $message = '<div style="color: green; margin-bottom: 10px;">Bloque eliminado correctamente.</div>';
        } catch (PDOException $e) {
            $message = '<div style="color: red; margin-bottom: 10px;">Error: ' . $e->getMessage() . '</div>';
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'auto_generate') {
        // Generar bloques estándar L-V 7:00-14:00
        $dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes'];
        $horas = [
            ['07:00', '07:50'], ['07:50', '08:40'], ['08:40', '09:30'], 
            ['09:30', '10:20'], ['10:20', '11:10'], ['11:10', '12:00'], 
            ['12:00', '12:50'], ['12:50', '13:40']
        ];
        
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO bloques_horario (dia, hora_inicio, hora_fin, turno) VALUES (?, ?, ?, 'Matutino')");
            foreach ($dias as $d) {
                foreach ($horas as $h) {
                    $stmt->execute([$d, $h[0], $h[1]]);
                }
            }
            $pdo->commit();
            $message = '<div style="color: green; margin-bottom: 10px;">Bloques estándar generados correctamente.</div>';
        } catch (Exception $e) {
            $pdo->rollBack();
            $message = '<div style="color: red; margin-bottom: 10px;">Error: ' . $e->getMessage() . '</div>';
        }
    }
}

$stmt = $pdo->query("SELECT * FROM bloques_horario ORDER BY FIELD(dia, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'), hora_inicio ASC");
$bloques = $stmt->fetchAll();

require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';
?>

<div class="main-content">
    <div class="header">
        <h1>Bloques de Horario (Colores)</h1>
        <div><span>Configuración</span></div>
    </div>

    <?php echo $message; ?>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Acciones Rápidas</h2>
        </div>
        <p>Si el sistema está vacío, puedes generar automáticamente los bloques estándar (Lunes a Viernes, 7:00 - 13:40).</p>
        <form method="POST" onsubmit="return confirm('¿Generar bloques masivamente? Esto puede duplicar si ya existen.');">
            <input type="hidden" name="action" value="auto_generate">
            <button type="submit" class="btn btn-primary">Generar Bloques Matutinos Estándar</button>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Nuevo Bloque Manual</h2>
        </div>
        <form method="POST" action="">
            <input type="hidden" name="action" value="create">
            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Día</label>
                    <select name="dia" class="form-control">
                        <option value="Lunes">Lunes</option>
                        <option value="Martes">Martes</option>
                        <option value="Miercoles">Miércoles</option>
                        <option value="Jueves">Jueves</option>
                        <option value="Viernes">Viernes</option>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Hora Inicio</label>
                    <input type="time" name="hora_inicio" class="form-control" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Hora Fin</label>
                    <input type="time" name="hora_fin" class="form-control" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Turno</label>
                    <select name="turno" class="form-control">
                        <option value="Matutino">Matutino</option>
                        <option value="Vespertino">Vespertino</option>
                    </select>
                </div>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary">Guardar Bloque</button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Bloques Definidos</h2>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Día</th>
                        <th>Horario</th>
                        <th>Turno</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bloques as $b): ?>
                    <tr>
                        <td><?php echo $b['id_bloque']; ?></td>
                        <td><?php echo $b['dia']; ?></td>
                        <td><?php echo substr($b['hora_inicio'], 0, 5) . ' - ' . substr($b['hora_fin'], 0, 5); ?></td>
                        <td><?php echo $b['turno']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar bloque?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id_bloque" value="<?php echo $b['id_bloque']; ?>">
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
