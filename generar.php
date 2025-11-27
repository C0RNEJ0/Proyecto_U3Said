<?php
$page = 'generar';
require_once 'config/db.php';
require_once 'src/AlgoritmoHorarios.php';

$message = '';
$status = 'waiting'; // waiting, success, error

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'run') {
    try {
        // Aumentar tiempo de ejecución para grafos grandes
        set_time_limit(300); 
        
        $generador = new GeneradorHorarios($pdo);
        $inicio = microtime(true);
        $exito = $generador->generar();
        $fin = microtime(true);
        $tiempo = round($fin - $inicio, 2);

        if ($exito) {
            $status = 'success';
            $message = "Horario generado exitosamente en $tiempo segundos.";
        } else {
            $status = 'error';
            $message = "No se pudo generar un horario válido con las restricciones actuales. Intenta agregar más bloques de horario o reducir la carga.";
        }
    } catch (Exception $e) {
        $status = 'error';
        $message = "Error del sistema: " . $e->getMessage();
    }
}

require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';
?>

<div class="main-content">
    <div class="header">
        <h1>Generación Automática</h1>
        <div><span>Algoritmo Inteligente</span></div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Ejecutar Algoritmo</h2>
        </div>
        
        <div style="text-align: center; padding: 40px;">
            <?php if ($status == 'waiting'): ?>
                <i class="fas fa-robot" style="font-size: 5rem; color: var(--upv-gray); margin-bottom: 20px;"></i>
                <p style="font-size: 1.2rem; margin-bottom: 30px;">
                    El sistema utilizará <strong>Coloreado de Grafos</strong> y <strong>Backtracking</strong> para asignar horarios sin conflictos.
                    <br>Esto puede tomar unos segundos.
                </p>
                <form method="POST">
                    <input type="hidden" name="action" value="run">
                    <button type="submit" class="btn btn-primary" style="font-size: 1.2rem; padding: 15px 40px;">
                        <i class="fas fa-play"></i> Generar Horarios Ahora
                    </button>
                </form>

            <?php elseif ($status == 'success'): ?>
                <i class="fas fa-check-circle" style="font-size: 5rem; color: var(--upv-green); margin-bottom: 20px;"></i>
                <h3 style="color: var(--upv-green);">¡Éxito!</h3>
                <p><?php echo $message; ?></p>
                <div style="margin-top: 30px;">
                    <a href="ver_horario.php" class="btn btn-primary">Ver Resultados</a>
                    <a href="generar.php" class="btn btn-sm btn-danger" style="margin-left: 10px;">Volver</a>
                </div>

            <?php elseif ($status == 'error'): ?>
                <i class="fas fa-times-circle" style="font-size: 5rem; color: #dc3545; margin-bottom: 20px;"></i>
                <h3 style="color: #dc3545;">Falló la Generación</h3>
                <p><?php echo $message; ?></p>
                <div style="margin-top: 30px;">
                    <a href="generar.php" class="btn btn-primary">Intentar de Nuevo</a>
                    <a href="bloques.php" class="btn btn-sm btn-primary">Gestionar Bloques</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
