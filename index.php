<?php
$page = 'home';
require_once 'config/db.php';
require_once 'views/layout/header.php';
require_once 'views/layout/sidebar.php';

// Obtener conteos rápidos
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM profesores");
    $total_profesores = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM grupos");
    $total_grupos = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM materias");
    $total_materias = $stmt->fetchColumn();
} catch (PDOException $e) {
    $total_profesores = 0;
    $total_grupos = 0;
    $total_materias = 0;
}
?>

<div class="main-content">
    <div class="header">
        <h1>Sistema de Generación de Horarios - UPV</h1>
        <div>
            <img src="assets/img/logo_upv.png" alt="Logo UPV" style="height: 50px; display:none;"> <!-- Placeholder -->
            <span>Bienvenido, Coordinador</span>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Resumen del Sistema</h2>
        </div>
        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
            <div style="flex: 1; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 5px solid var(--upv-green);">
                <h3>Profesores</h3>
                <p style="font-size: 2rem; font-weight: bold; margin: 10px 0;"><?php echo $total_profesores; ?></p>
                <a href="profesores.php" class="btn btn-sm btn-primary">Gestionar</a>
            </div>
            <div style="flex: 1; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 5px solid var(--upv-green);">
                <h3>Grupos</h3>
                <p style="font-size: 2rem; font-weight: bold; margin: 10px 0;"><?php echo $total_grupos; ?></p>
                <a href="grupos.php" class="btn btn-sm btn-primary">Gestionar</a>
            </div>
            <div style="flex: 1; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 5px solid var(--upv-green);">
                <h3>Materias</h3>
                <p style="font-size: 2rem; font-weight: bold; margin: 10px 0;"><?php echo $total_materias; ?></p>
                <a href="materias.php" class="btn btn-sm btn-primary">Gestionar</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Acciones Rápidas</h2>
        </div>
        <p>Seleccione una opción del menú lateral para comenzar a cargar la información académica.</p>
        <div style="margin-top: 20px;">
            <a href="generar.php" class="btn btn-primary" style="padding: 15px 30px; font-size: 1.1rem;">
                <i class="fas fa-magic"></i> Generar Nuevo Horario
            </a>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
