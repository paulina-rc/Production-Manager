<?php

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

if (!isset($_GET['id'])) {
    header('Location: list.php');
    exit;
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("
    SELECT
        productions.*,

        products.name AS product_name,

        processor.full_name AS processed_by_name,

        creator.full_name AS created_by_name,

        sections.name AS section_name

    FROM productions

    INNER JOIN products
        ON productions.product_id = products.id

    INNER JOIN users processor
        ON productions.processed_by = processor.id

    INNER JOIN users creator
        ON productions.created_by = creator.id

    INNER JOIN sections
        ON productions.section_id = sections.id

    WHERE productions.id = ?
    LIMIT 1
");

$stmt->execute([$id]);

$production = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$production) {
    header('Location: list.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Producción</title>
</head>
<body>

<h1>Detalle de Producción</h1>

<a href="list.php">Volver al Historial</a>

<br><br>

<p>
    <strong>Fecha de Producción:</strong>
    <?php echo htmlspecialchars($production['production_date']); ?>
</p>

<p>
    <strong>Producto:</strong>
    <?php echo htmlspecialchars($production['product_name']); ?>
</p>

<p>
    <strong>Materias Primas:</strong><br>
    <?php echo nl2br(htmlspecialchars($production['raw_materials'])); ?>
</p>

<p>
    <strong>Procesado por:</strong>
    <?php echo htmlspecialchars($production['processed_by_name']); ?>
</p>

<p>
    <strong>Registrado por:</strong>
    <?php echo htmlspecialchars($production['created_by_name']); ?>
</p>

<p>
    <strong>Sección:</strong>
    <?php echo htmlspecialchars($production['section_name']); ?>
</p>

<p>
    <strong>Cantidad:</strong>
    <?php echo htmlspecialchars($production['quantity']); ?>
</p>

<p>
    <strong>Unidad:</strong>

    <?php
    if (
        $production['unit'] === 'Other' &&
        !empty($production['custom_unit'])
    ) {
        echo htmlspecialchars($production['custom_unit']);
    } else {
        echo htmlspecialchars($production['unit']);
    }
    ?>
</p>

<p>
    <strong>Fecha de Registro:</strong>
    <?php echo htmlspecialchars($production['created_at']); ?>
</p>

</body>
</html>