<?php

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/permissions.php';

$week = $_GET['week'] ?? date('W');
$year = $_GET['year'] ?? date('Y');

$stmt = $pdo->prepare("
    SELECT
        productions.production_date,
        products.name AS product_name,
        sections.name AS section_name,
        productions.quantity,
        productions.unit
    FROM productions
    INNER JOIN products
        ON productions.product_id = products.id
    INNER JOIN sections
        ON productions.section_id = sections.id
    WHERE WEEK(productions.production_date, 1) = ?
    AND YEAR(productions.production_date) = ?
    AND productions.deleted_at IS NULL
    ORDER BY productions.production_date DESC
");

$stmt->execute([
    $week,
    $year
]);

$productions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$export = $_GET['export'] ?? '';

if ($export === 'excel' || $export === 'pdf') {

    requireExportPermission();

    require_once dirname(__DIR__) . '/config/export.php';

    $headers = ['Fecha', 'Producto', 'Sección', 'Cantidad', 'Unidad'];

    $data = array_map(function ($p) {
        return [
            $p['production_date'],
            $p['product_name'],
            $p['section_name'],
            $p['quantity'],
            $p['unit'],
        ];
    }, $productions);

    if ($export === 'excel') {
        exportToExcel($data, $headers, 'reporte-semanal');
    } else {
        exportToPdf($data, $headers, 'Reporte Semanal', 'reporte-semanal');
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Reporte Semanal</title>

    <?php require_once '../includes/header.php'; ?>

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

    <div class="welcome-box">

        <h1>Reporte Semanal</h1>

        <p>
            Consulta de producciones registradas por semana.
        </p>

    </div>

    <div class="table-card">

        <div class="table-header">

            <h2>Filtros</h2>

            <a class="btn" href="index.php">
                Volver a Reportes
            </a>

        </div>

        <form method="GET">

            <div style="display:flex; gap:15px; flex-wrap:wrap; align-items:end;">

                <div>

                    <label>Semana</label>

                    <input
                        type="number"
                        name="week"
                        min="1"
                        max="53"
                        value="<?php echo htmlspecialchars($week); ?>"
                        class="form-control"
                    >

                </div>

                <div>

                    <label>Año</label>

                    <input
                        type="number"
                        name="year"
                        value="<?php echo htmlspecialchars($year); ?>"
                        class="form-control"
                    >

                </div>

                <div>

                    <button
                        type="submit"
                        class="btn"
                    >
                        Generar Reporte
                    </button>

                </div>

            </div>

        </form>

    </div>

    <div class="stats-container">

        <div class="stat-card">

            <h3>Total Registros</h3>

            <div class="stat-number">
                <?php echo count($productions); ?>
            </div>

        </div>

    </div>

    <div class="table-card">

        <div class="table-header">

            <h2>Resultados</h2>

            <?php if (isAdmin() || isAdministracion()): ?>

                <div class="page-header-actions">

                    <a class="btn btn-sm"
                       href="?week=<?php echo urlencode($week); ?>&year=<?php echo urlencode($year); ?>&export=excel">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>

                    <a class="btn btn-sm"
                       href="?week=<?php echo urlencode($week); ?>&year=<?php echo urlencode($year); ?>&export=pdf">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>

                </div>

            <?php endif; ?>

        </div>

        <?php if (empty($productions)): ?>

            <p>
                No se encontraron registros para esta semana.
            </p>

        <?php else: ?>

            <table class="table">

                <thead>

                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Sección</th>
                        <th>Cantidad</th>
                        <th>Unidad</th>
                    </tr>

                </thead>

                <tbody>

                <?php foreach ($productions as $production): ?>

                    <tr>

                        <td>
                            <?php echo htmlspecialchars($production['production_date']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($production['product_name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($production['section_name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($production['quantity']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($production['unit']); ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        <?php endif; ?>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>
