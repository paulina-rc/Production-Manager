<?php

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $productionDate = $_POST['production_date'];
    $productId = (int) $_POST['product_id'];
    $rawMaterials = trim($_POST['raw_materials']);
    $sectionId = (int) $_POST['section_id'];
    $quantity = (float) $_POST['quantity'];
    $unit = trim($_POST['unit']);
    $customUnit = trim($_POST['custom_unit']);

    $processedBy = $_SESSION['user_id'];

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
            INSERT INTO productions (
                production_date,
                product_id,
                raw_materials,
                processed_by,
                section_id,
                quantity,
                unit,
                custom_unit,
                created_by
            )
            VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?
            )
        ");

        $stmt->execute([
            $productionDate,
            $productId,
            $rawMaterials,
            $processedBy,
            $sectionId,
            $quantity,
            $unit,
            $customUnit ?: null,
            $_SESSION['user_id']
        ]);

        header('Location: list.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Producción</title>
</head>
<body>

<h1>Registrar Producción</h1>

<a href="list.php">Volver al Historial</a>

<br><br>

<?php if (!empty($error)): ?>
    <p><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST">

    <label>Fecha de Producción</label>
    <br>
    <input type="date" name="production_date" required>

    <br><br>

    <label>Producto</label>
    <br>

    <select name="product_id" required>

        <option value="">Seleccione un producto</option>

        <?php foreach ($products as $product): ?>

            <option value="<?php echo $product['id']; ?>">
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
    ></textarea>

    <br><br>

    <label>Sección</label>
    <br>

    <select name="section_id" required>

        <option value="">Seleccione una sección</option>

        <?php foreach ($sections as $section): ?>

            <option value="<?php echo $section['id']; ?>">
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
        required
    >

    <br><br>

    <label>Unidad</label>
    <br>

    <select name="unit" required>

        <option value="">Seleccione una unidad</option>

        <option value="Units">Unidades</option>
        <option value="Kilograms">Kilogramos</option>
        <option value="Grams">Gramos</option>
        <option value="Liters">Litros</option>
        <option value="Milliliters">Mililitros</option>
        <option value="Other">Otro</option>

    </select>

    <br><br>

    <label>Unidad Personalizada (solo si seleccionó "Otro")</label>
    <br>

    <input
        type="text"
        name="custom_unit"
        maxlength="50"
    >

    <br><br>

    <button type="submit">
        Guardar Producción
    </button>

</form>

</body>
</html>