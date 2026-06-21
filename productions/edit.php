<?php

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

if (!isset($_GET['id'])) {
    header('Location: list.php');
    exit;
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("
    SELECT *
    FROM productions
    WHERE id = ?
    AND deleted_at IS NULL
    LIMIT 1
");

$stmt->execute([$id]);

$production = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$production) {
    header('Location: list.php');
    exit;
}

$currentUserId = $_SESSION['user_id'];
$currentRoleId = $_SESSION['role_id'];

if ($currentRoleId != 1) {

    if ($production['created_by'] != $currentUserId) {
        header('Location: list.php');
        exit;
    }
}

$products = $pdo->query("
    SELECT id, name
    FROM products
    WHERE active = 1
    ORDER BY name
")->fetchAll(PDO::FETCH_ASSOC);

$sections = $pdo->query("
    SELECT id, name
    FROM sections
    WHERE active = 1
    ORDER BY name
")->fetchAll(PDO::FETCH_ASSOC);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $productionDate = $_POST['production_date'];
    $productId = (int) $_POST['product_id'];
    $rawMaterials = trim($_POST['raw_materials']);
    $sectionId = (int) $_POST['section_id'];
    $quantity = (float) $_POST['quantity'];
    $unit = trim($_POST['unit']);
    $customUnit = trim($_POST['custom_unit']);

    if (
        empty($productionDate) ||
        empty($productId) ||
        empty($rawMaterials) ||
        empty($sectionId) ||
        empty($quantity) ||
        empty($unit)
    ) {

        $error = 'Todos los campos son obligatorios';

    } else {

        $stmt = $pdo->prepare("
            UPDATE productions
            SET
                production_date = ?,
                product_id = ?,
                raw_materials = ?,
                section_id = ?,
                quantity = ?,
                unit = ?,
                custom_unit = ?,
                updated_at = NOW()
            WHERE id = ?
        ");

        $stmt->execute([
            $productionDate,
            $productId,
            $rawMaterials,
            $sectionId,
            $quantity,
            $unit,
            $customUnit ?: null,
            $id
        ]);

        header('Location: view.php?id=' . $id);
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Editar Producción</title>

    <link rel="stylesheet"
          HEADER_REPLACED>

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

    <div class="welcome-box">

        <h1>Editar Producción</h1>

        <p>
            Modifique la información del registro seleccionado.
        </p>

    </div>

    <div class="form-card">

        <div class="table-header">

            <h2>Formulario de Edición</h2>

            <a class="btn"
               href="view.php?id=<?php echo $id; ?>">
                Volver al Detalle
            </a>

        </div>

        <?php if (!empty($error)): ?>

            <p style="
                color:#dc2626;
                font-weight:bold;
                margin-bottom:20px;
            ">
                <?php echo $error; ?>
            </p>

        <?php endif; ?>

        <form method="POST">

            <div class="form-grid">

                <div class="form-group">

                    <label>
                        Fecha de Producción
                    </label>

                    <input
                        type="date"
                        name="production_date"
                        value="<?php echo htmlspecialchars($production['production_date']); ?>"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>
                        Producto
                    </label>

                    <select
                        name="product_id"
                        required
                    >

                        <?php foreach ($products as $product): ?>

                            <option
                                value="<?php echo $product['id']; ?>"
                                <?php echo ($product['id'] == $production['product_id']) ? 'selected' : ''; ?>
                            >
                                <?php echo htmlspecialchars($product['name']); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="form-group">

                    <label>
                        Sección
                    </label>

                    <select
                        name="section_id"
                        required
                    >

                        <?php foreach ($sections as $section): ?>

                            <option
                                value="<?php echo $section['id']; ?>"
                                <?php echo ($section['id'] == $production['section_id']) ? 'selected' : ''; ?>
                            >
                                <?php echo htmlspecialchars($section['name']); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="form-group">

                    <label>
                        Cantidad
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0.01"
                        name="quantity"
                        value="<?php echo htmlspecialchars($production['quantity']); ?>"
                        required
                    >

                </div>

                <div class="form-group form-full">

                    <label>
                        Unidad
                    </label>

                    <select
                        name="unit"
                        required
                    >

                        <option value="Units" <?php echo ($production['unit'] == 'Units') ? 'selected' : ''; ?>>
                            Unidades
                        </option>

                        <option value="Kilograms" <?php echo ($production['unit'] == 'Kilograms') ? 'selected' : ''; ?>>
                            Kilogramos
                        </option>

                        <option value="Grams" <?php echo ($production['unit'] == 'Grams') ? 'selected' : ''; ?>>
                            Gramos
                        </option>

                        <option value="Liters" <?php echo ($production['unit'] == 'Liters') ? 'selected' : ''; ?>>
                            Litros
                        </option>

                        <option value="Milliliters" <?php echo ($production['unit'] == 'Milliliters') ? 'selected' : ''; ?>>
                            Mililitros
                        </option>

                        <option value="Other" <?php echo ($production['unit'] == 'Other') ? 'selected' : ''; ?>>
                            Otro
                        </option>

                    </select>

                </div>

                <div class="form-group form-full">

                    <label>
                        Unidad Personalizada
                    </label>

                    <input
                        type="text"
                        name="custom_unit"
                        value="<?php echo htmlspecialchars($production['custom_unit']); ?>"
                        maxlength="50"
                    >

                </div>

                <div class="form-group form-full">

                    <label>
                        Materias Primas
                    </label>

                    <textarea
                        name="raw_materials"
                        required
                    ><?php echo htmlspecialchars($production['raw_materials']); ?></textarea>

                </div>

            </div>

            <div class="form-actions">

                <button
                    type="submit"
                    class="btn"
                >
                    Guardar Cambios
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>

</body>
</html>