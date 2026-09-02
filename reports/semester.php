<?php

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/permissions.php';

requireAdministracion();

$semester = $_GET['semester'] ?? 1;
$year = $_GET['year'] ?? date('Y');

if ($semester == 1) {
    $startMonth = 1;
    $endMonth = 6;
} else {
    $startMonth = 7;
    $endMonth = 12;
}

$stmt = $pdo->prepare("
    SELECT
        productions.production_date,
        products.name AS product_name,
        sections.name AS section_name,
        productions.quantity,
        productions.unit,
        processor.full_name AS processed_by_name,
        creator.full_name AS created_by_name
    FROM productions
    INNER JOIN products
        ON productions.product_id = products.id
    INNER JOIN sections
        ON productions.section_id = sections.id
    INNER JOIN users processor
        ON productions.processed_by = processor.id
    INNER JOIN users creator
        ON productions.created_by = creator.id
    WHERE YEAR(productions.production_date) = ?
    AND MONTH(productions.production_date)
        BETWEEN ? AND ?
    AND productions.deleted_at IS NULL
    ORDER BY productions.production_date DESC
");

$stmt->execute([
    $year,
    $startMonth,
    $endMonth
]);

$productions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$productTotalsStmt = $pdo->prepare("
    SELECT
        products.name AS product_name,
        CASE
            WHEN productions.unit = 'Otro' THEN productions.custom_unit
            ELSE productions.unit
        END AS unit_label,
        SUM(productions.quantity) AS total_quantity
    FROM productions
    INNER JOIN products
        ON productions.product_id = products.id
    WHERE YEAR(productions.production_date) = ?
    AND MONTH(productions.production_date)
        BETWEEN ? AND ?
    AND productions.deleted_at IS NULL
    GROUP BY products.id, products.name, productions.unit, productions.custom_unit
    ORDER BY total_quantity DESC
");

$productTotalsStmt->execute([
    $year,
    $startMonth,
    $endMonth
]);

$productTotals = $productTotalsStmt->fetchAll(PDO::FETCH_ASSOC);

$sectionTotalsStmt = $pdo->prepare("
    SELECT
        sections.name AS section_name,
        CASE
            WHEN productions.unit = 'Otro' THEN productions.custom_unit
            ELSE productions.unit
        END AS unit_label,
        SUM(productions.quantity) AS total_quantity
    FROM productions
    INNER JOIN sections
        ON productions.section_id = sections.id
    WHERE YEAR(productions.production_date) = ?
    AND MONTH(productions.production_date)
        BETWEEN ? AND ?
    AND productions.deleted_at IS NULL
    GROUP BY sections.id, sections.name, productions.unit, productions.custom_unit
    ORDER BY total_quantity DESC
");

$sectionTotalsStmt->execute([
    $year,
    $startMonth,
    $endMonth
]);

$sectionTotals = $sectionTotalsStmt->fetchAll(PDO::FETCH_ASSOC);

$export = $_GET['export'] ?? '';

if ($export === 'excel' || $export === 'pdf') {

    requireExportPermission();

    require_once dirname(__DIR__) . '/config/export.php';

    $sections = [
        [
            'title' => 'Detalle',
            'headers' => ['Fecha', 'Producto', 'Procesado Por', 'Registrado Por', 'Sección', 'Cantidad', 'Unidad'],
            'rows' => array_map(function ($p) {
                return [
                    $p['production_date'],
                    $p['product_name'],
                    $p['processed_by_name'],
                    $p['created_by_name'],
                    $p['section_name'],
                    $p['quantity'],
                    $p['unit'],
                ];
            }, $productions),
        ],
        [
            'title' => 'Producción Total por Producto',
            'headers' => ['Producto', 'Cantidad', 'Unidad'],
            'rows' => array_map(function ($t) {
                return [$t['product_name'], $t['total_quantity'], $t['unit_label']];
            }, $productTotals),
        ],
        [
            'title' => 'Producción Total por Sección',
            'headers' => ['Sección', 'Cantidad', 'Unidad'],
            'rows' => array_map(function ($t) {
                return [$t['section_name'], $t['total_quantity'], $t['unit_label']];
            }, $sectionTotals),
        ],
    ];

    if ($export === 'excel') {
        exportToExcel($sections, 'reporte-semestral');
    } else {
        exportToPdf($sections, 'Reporte Semestral', 'reporte-semestral');
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Reporte Semestral</title>

    <?php require_once '../includes/header.php'; ?>

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

    <div class="welcome-box">

        <h1>Reporte Semestral</h1>

        <p>
            Consulta de producciones por semestre.
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

                    <label>Semestre</label>

                    <select
                        name="semester"
                        class="form-control"
                    >

                        <option value="1"
                            <?php echo ($semester == 1) ? 'selected' : ''; ?>>
                            Primer Semestre
                        </option>

                        <option value="2"
                            <?php echo ($semester == 2) ? 'selected' : ''; ?>>
                            Segundo Semestre
                        </option>

                    </select>

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
            <h2>Producción Total por Producto</h2>
        </div>

        <?php if (empty($productions)): ?>

            <p>No se encontraron registros.</p>

        <?php else: ?>

            <table class="table">

                <thead>

                    <tr>
                        <th>Producto</th>
                        <th class="text-right">Cantidad</th>
                        <th>Unidad</th>
                    </tr>

                </thead>

                <tbody>

                <?php foreach ($productTotals as $total): ?>

                    <tr>

                        <td><?php echo htmlspecialchars($total['product_name']); ?></td>

                        <td class="text-right"><?php echo htmlspecialchars($total['total_quantity']); ?></td>

                        <td><?php echo htmlspecialchars($total['unit_label']); ?></td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        <?php endif; ?>

    </div>

    <div class="table-card">

        <div class="table-header">
            <h2>Producción Total por Sección</h2>
        </div>

        <?php if (empty($productions)): ?>

            <p>No se encontraron registros.</p>

        <?php else: ?>

            <table class="table">

                <thead>

                    <tr>
                        <th>Sección</th>
                        <th class="text-right">Cantidad</th>
                        <th>Unidad</th>
                    </tr>

                </thead>

                <tbody>

                <?php foreach ($sectionTotals as $total): ?>

                    <tr>

                        <td><?php echo htmlspecialchars($total['section_name']); ?></td>

                        <td class="text-right"><?php echo htmlspecialchars($total['total_quantity']); ?></td>

                        <td><?php echo htmlspecialchars($total['unit_label']); ?></td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        <?php endif; ?>

    </div>

    <div class="table-card">

        <div class="table-header">

            <h2>Resultados</h2>

            <?php if (isAdministracion()): ?>

                <div class="page-header-actions">

                    <a class="btn btn-sm"
                       href="?semester=<?php echo urlencode($semester); ?>&year=<?php echo urlencode($year); ?>&export=excel">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>

                    <a class="btn btn-sm"
                       href="?semester=<?php echo urlencode($semester); ?>&year=<?php echo urlencode($year); ?>&export=pdf">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>

                </div>

            <?php endif; ?>

        </div>

        <?php if (empty($productions)): ?>

            <p>No se encontraron registros.</p>

        <?php else: ?>

            <table class="table">

                <thead>

                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Procesado Por</th>
                        <th>Registrado Por</th>
                        <th>Sección</th>
                        <th>Cantidad</th>
                        <th>Unidad</th>
                    </tr>

                </thead>

                <tbody>

                <?php foreach ($productions as $production): ?>

                    <tr>

                        <td><?php echo htmlspecialchars($production['production_date']); ?></td>

                        <td><?php echo htmlspecialchars($production['product_name']); ?></td>

                        <td><?php echo htmlspecialchars($production['processed_by_name']); ?></td>

                        <td><?php echo htmlspecialchars($production['created_by_name']); ?></td>

                        <td><?php echo htmlspecialchars($production['section_name']); ?></td>

                        <td><?php echo htmlspecialchars($production['quantity']); ?></td>

                        <td><?php echo htmlspecialchars($production['unit']); ?></td>

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
