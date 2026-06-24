<?php

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/permissions.php';

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Reportes</title>

    <?php require_once '../includes/header.php'; ?>

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

    <div class="welcome-box">

        <h1>Reportes</h1>

        <p>
            Seleccione el tipo de reporte que desea consultar.
        </p>

    </div>

    <div class="report-grid">

        <a href="weekly.php" class="report-card">

            <div class="report-card-icon">
                <i class="fas fa-calendar-week"></i>
            </div>

            <h3>Reporte Semanal</h3>

            <p>
                Producciones registradas por semana.
            </p>

            <span class="btn">Ver Reporte</span>

        </a>

        <a href="monthly.php" class="report-card">

            <div class="report-card-icon">
                <i class="fas fa-calendar-days"></i>
            </div>

            <h3>Reporte Mensual</h3>

            <p>
                Producciones registradas por mes.
            </p>

            <span class="btn">Ver Reporte</span>

        </a>

        <a href="semester.php" class="report-card">

            <div class="report-card-icon">
                <i class="fas fa-calendar"></i>
            </div>

            <h3>Reporte Semestral</h3>

            <p>
                Producciones registradas por semestre.
            </p>

            <span class="btn">Ver Reporte</span>

        </a>

        <a href="yearly.php" class="report-card">

            <div class="report-card-icon">
                <i class="fas fa-calendar-check"></i>
            </div>

            <h3>Reporte Anual</h3>

            <p>
                Producciones registradas por año.
            </p>

            <span class="btn">Ver Reporte</span>

        </a>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>
