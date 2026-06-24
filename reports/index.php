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

    <div class="card-container">

        <div class="card">

            <h3>
                <i class="fas fa-calendar-week"></i>
                Reporte Semanal
            </h3>

            <p>
                Producciones registradas por semana.
            </p>

            <a class="btn" href="weekly.php">
                Ver Reporte
            </a>

        </div>

        <div class="card">

            <h3>
                <i class="fas fa-calendar-days"></i>
                Reporte Mensual
            </h3>

            <p>
                Producciones registradas por mes.
            </p>

            <a class="btn" href="monthly.php">
                Ver Reporte
            </a>

        </div>

        <div class="card">

            <h3>
                <i class="fas fa-calendar"></i>
                Reporte Semestral
            </h3>

            <p>
                Producciones registradas por semestre.
            </p>

            <a class="btn" href="semester.php">
                Ver Reporte
            </a>

        </div>

        <div class="card">

            <h3>
                <i class="fas fa-calendar-check"></i>
                Reporte Anual
            </h3>

            <p>
                Producciones registradas por año.
            </p>

            <a class="btn" href="yearly.php">
                Ver Reporte
            </a>

        </div>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>
