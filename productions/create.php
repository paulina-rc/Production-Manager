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

    <link rel="stylesheet"
          HEADER_REPLACED>

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

    <div class="welcome-box">

        <h1>Nueva Producción</h1>

        <p>
            Registrar una nueva producción agroindustrial.
        </p>

    </div>

    <div class="profile-card">

        <div class="table-header">

            <h2>Formulario de Registro</h2>

            <a class="btn"
               href="list.php">
                Volver al Historial
            </a>

        </div>

        <?php if (!empty($error)): ?>

            <p style="
                color:#dc2626;
                margin-bottom:20px;
                font-weight:bold;
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

                <option value="">
                    Seleccione un producto
                </option>

                <?php foreach ($products as $product): ?>

                    <option
                        value="<?php echo $product['id']; ?>"
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

                <option value="">
                    Seleccione una sección
                </option>

                <?php foreach ($sections as $section): ?>

                    <option
                        value="<?php echo $section['id']; ?>"
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

                <option value="">
                    Seleccione una unidad
                </option>

                <option value="Units">Unidades</option>
                <option value="Kilograms">Kilogramos</option>
                <option value="Grams">Gramos</option>
                <option value="Liters">Litros</option>
                <option value="Milliliters">Mililitros</option>
                <option value="Other">Otro</option>

            </select>

        </div>

        <div class="form-group form-full">

            <label>
                Unidad Personalizada
            </label>

            <input
                type="text"
                name="custom_unit"
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
            ></textarea>

        </div>

    </div>

    <div class="form-actions">

        <button
            type="submit"
            class="btn"
        >
            Guardar Producción
        </button>

    </div>

</form>

    </div>

</div>

</body>
</html>