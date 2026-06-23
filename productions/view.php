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

    <link rel="stylesheet"
          <?php require_once '../includes/header.php'; ?>>

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

    <div class="welcome-box">

        <h1>Detalle de Producción</h1>

        <p>
            Información completa del registro seleccionado.
        </p>

    </div>

    <div class="profile-card">

        <div class="table-header">

            <h2>Información General</h2>

            <a class="btn"
               href="list.php">
                Volver al Historial
            </a>

        </div>

        <hr>

        <h3>Producto</h3>

        <p>
            <?php echo htmlspecialchars($production['product_name']); ?>
        </p>

        <hr>

        <h3>Fecha de Producción</h3>

        <p>
            <?php echo htmlspecialchars($production['production_date']); ?>
        </p>

        <hr>

        <h3>Materias Primas</h3>

        <p>
            <?php echo nl2br(
                htmlspecialchars($production['raw_materials'])
            ); ?>
        </p>

        <hr>

        <h3>Procesado por</h3>

        <p>
            <?php echo htmlspecialchars($production['processed_by_name']); ?>
        </p>

        <hr>

        <h3>Registrado por</h3>

        <p>
            <?php echo htmlspecialchars($production['created_by_name']); ?>
        </p>

        <hr>

        <h3>Sección</h3>

        <p>
            <?php echo htmlspecialchars($production['section_name']); ?>
        </p>

        <hr>

        <h3>Cantidad</h3>

        <p>
            <?php echo htmlspecialchars($production['quantity']); ?>
        </p>

        <hr>

        <h3>Unidad</h3>

        <p>

            <?php

            if (
                $production['unit'] === 'Other' &&
                !empty($production['custom_unit'])
            ) {

                echo htmlspecialchars(
                    $production['custom_unit']
                );

            } else {

                echo htmlspecialchars(
                    $production['unit']
                );

            }

            ?>

        </p>

        <hr>

        <h3>Fecha de Registro</h3>

        <p>
            <?php echo htmlspecialchars($production['created_at']); ?>
        </p>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>