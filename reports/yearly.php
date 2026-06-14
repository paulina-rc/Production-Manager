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
</head>
<body>

<h1>Reporte Anual</h1>

<a href="../dashboard/index.php">
    Dashboard
</a>

<br><br>

<form method="GET">

    <label>Año</label>

    <input
        type="number"
        name="year"
        value="<?php echo $year; ?>"
    >

    <button type="submit">
        Generar
    </button>

</form>

<br>

<table border="1">

    <tr>
        <th>Fecha</th>
        <th>Producto</th>
        <th>Procesado Por</th>
        <th>Registrado Por</th>
        <th>Sección</th>
        <th>Cantidad</th>
        <th>Unidad</th>
    </tr>

    <?php foreach ($productions as $production): ?>

        <tr>

            <td>
                <?php echo htmlspecialchars($production['production_date']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($production['product_name']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($production['processed_by_name']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($production['created_by_name']); ?>
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

</table>

</body>
</html>