<?php

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

$month = $_GET['month'] ?? date('m');
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

    WHERE MONTH(productions.production_date) = ?
    AND YEAR(productions.production_date) = ?
    AND productions.deleted_at IS NULL

    ORDER BY productions.production_date DESC
");

$stmt->execute([
    $month,
    $year
]);

$productions = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Mensual</title>
</head>
<body>

<h1>Reporte Mensual</h1>

<a href="../dashboard/index.php">
    Dashboard
</a>

<br><br>

<form method="GET">

    <label>Mes</label>

    <select name="month">

        <?php for ($i = 1; $i <= 12; $i++): ?>

            <option
                value="<?php echo sprintf('%02d', $i); ?>"
                <?php echo ($month == sprintf('%02d', $i)) ? 'selected' : ''; ?>
            >
                <?php echo $i; ?>
            </option>

        <?php endfor; ?>

    </select>

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
        <th>Sección</th>
        <th>Cantidad</th>
        <th>Unidad</th>
    </tr>

    <?php foreach ($productions as $production): ?>

        <tr>

            <td>
                <?php echo $production['production_date']; ?>
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

</table>

</body>
</html>