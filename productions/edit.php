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
</head>
<body>

<h1>Editar Producción</h1>

<a href="list.php">Volver al Historial</a>

<br><br>

<?php if (!empty($error)): ?>
    <p><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST">

    <label>Fecha de Producción</label>
    <br>

    <input
        type="date"
        name="production_date"
        value="<?php echo htmlspecialchars($production['production_date']); ?>"
        required
    >

    <br><br>

    <label>Producto</label>
    <br>

    <select name="product_id" required>

        <?php foreach ($products as $product): ?>

            <option
                value="<?php echo $product['id']; ?>"
                <?php echo ($product['id'] == $production['product_id']) ? 'selected' : ''; ?>
            >
                <?php echo htmlspecialchars($product['name']); ?>
            </option>

        <?php endforeach; ?>

    </select>

    <br><br>

    <label>Materias Primas</label>
    <br>

    <textarea
        name="raw_materials"
        rows="4"
        cols="50"
        required
    ><?php echo htmlspecialchars($production['raw_materials']); ?></textarea>

    <br><br>

    <label>Sección</label>
    <br>

    <select name="section_id" required>

        <?php foreach ($sections as $section): ?>

            <option
                value="<?php echo $section['id']; ?>"
                <?php echo ($section['id'] == $production['section_id']) ? 'selected' : ''; ?>
            >
                <?php echo htmlspecialchars($section['name']); ?>
            </option>

        <?php endforeach; ?>

    </select>

    <br><br>

    <label>Cantidad</label>
    <br>

    <input
        type="number"
        step="0.01"
        min="0.01"
        name="quantity"
        value="<?php echo htmlspecialchars($production['quantity']); ?>"
        required
    >

    <br><br>

    <label>Unidad</label>
    <br>

    <select name="unit" required>

        <option value="Units" <?php echo ($production['unit'] == 'Units') ? 'selected' : ''; ?>>Unidades</option>

        <option value="Kilograms" <?php echo ($production['unit'] == 'Kilograms') ? 'selected' : ''; ?>>Kilogramos</option>

        <option value="Grams" <?php echo ($production['unit'] == 'Grams') ? 'selected' : ''; ?>>Gramos</option>

        <option value="Liters" <?php echo ($production['unit'] == 'Liters') ? 'selected' : ''; ?>>Litros</option>

        <option value="Milliliters" <?php echo ($production['unit'] == 'Milliliters') ? 'selected' : ''; ?>>Mililitros</option>

        <option value="Other" <?php echo ($production['unit'] == 'Other') ? 'selected' : ''; ?>>Otro</option>

    </select>

    <br><br>

    <label>Unidad Personalizada</label>
    <br>

    <input
        type="text"
        name="custom_unit"
        value="<?php echo htmlspecialchars($production['custom_unit']); ?>"
        maxlength="50"
    >

    <br><br>

    <button type="submit">
        Guardar Cambios
    </button>

</form>

</body>
</html>