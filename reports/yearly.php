
<?php

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

$year = $_GET['year'] ?? date('Y');

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
    AND productions.deleted_at IS NULL
    ORDER BY productions.production_date DESC
");

$stmt->execute([$year]);

$productions = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Reporte Anual</title>

    <link rel="stylesheet"
          HEADER_REPLACED>

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

    <div class="welcome-box">

        <h1>Reporte Anual</h1>

        <p>
            Consulta de producciones registradas por año.
        </p>

    </div>

    <div class="table-card">

        <div class="table-header">

            <h2>Filtros</h2>

        </div>

        <form method="GET">

            <div style="display:flex; gap:15px; flex-wrap:wrap; align-items:end;">

                <div>

                    <label>Año</label>

                    <input
                        type="number"
                        name="year"
                        value="<?php echo $year; ?>"
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

</body>
</html>

