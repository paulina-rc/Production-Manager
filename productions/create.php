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

$professors = $pdo->query("
    SELECT users.id, users.full_name
    FROM users
    INNER JOIN roles
        ON users.role_id = roles.id
    WHERE users.status = 1
    AND roles.role_name = 'professor'
    ORDER BY users.full_name
")->fetchAll(PDO::FETCH_ASSOC);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $productionDate = $_POST['production_date'];
    $productId = (int) $_POST['product_id'];
    $rawMaterials = trim($_POST['raw_materials']);
    $processedBy = (int) $_POST['processed_by'];
    $sectionId = (int) $_POST['section_id'];
    $quantity = (float) $_POST['quantity'];
    $unit = trim($_POST['unit']);
    $customUnit = trim($_POST['custom_unit']);

    if (
        empty($productionDate) ||
        empty($productId) ||
        empty($rawMaterials) ||
        empty($processedBy) ||
        empty($sectionId) ||
        empty($quantity) ||
        empty($unit)
    ) {

        $error = 'All fields are required';

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
<html>
<head>
    <meta charset="UTF-8">
    <title>Create Production</title>
</head>
<body>

<h1>Create Production</h1>

<a href="list.php">Back to History</a>

<br><br>

<?php if (!empty($error)): ?>
    <p><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST">

    <label>Production Date</label>
    <br>
    <input type="date" name="production_date" required>

    <br><br>

    <label>Product</label>
    <br>

    <select name="product_id" required>

        <option value="">Select Product</option>

        <?php foreach ($products as $product): ?>

            <option value="<?php echo $product['id']; ?>">
                <?php echo htmlspecialchars($product['name']); ?>
            </option>

        <?php endforeach; ?>

    </select>

    <br><br>

    <label>Raw Materials</label>
    <br>

    <textarea
        name="raw_materials"
        rows="4"
        cols="50"
        required
    ></textarea>

    <br><br>

    <label>Processed By</label>
    <br>

    <select name="processed_by" required>

        <option value="">Select Professor</option>

        <?php foreach ($professors as $professor): ?>

            <option value="<?php echo $professor['id']; ?>">
                <?php echo htmlspecialchars($professor['full_name']); ?>
            </option>

        <?php endforeach; ?>

    </select>

    <br><br>

    <label>Section</label>
    <br>

    <select name="section_id" required>

        <option value="">Select Section</option>

        <?php foreach ($sections as $section): ?>

            <option value="<?php echo $section['id']; ?>">
                <?php echo htmlspecialchars($section['name']); ?>
            </option>

        <?php endforeach; ?>

    </select>

    <br><br>

    <label>Quantity</label>
    <br>

    <input
        type="number"
        step="0.01"
        min="0.01"
        name="quantity"
        required
    >

    <br><br>

    <label>Unit</label>
    <br>

    <select name="unit" required>

        <option value="">Select Unit</option>

        <option value="Units">Units</option>
        <option value="Kilograms">Kilograms</option>
        <option value="Grams">Grams</option>
        <option value="Liters">Liters</option>
        <option value="Milliliters">Milliliters</option>
        <option value="Other">Other</option>

    </select>

    <br><br>

    <label>Custom Unit (Only if Other)</label>
    <br>

    <input
        type="text"
        name="custom_unit"
        maxlength="50"
    >

    <br><br>

    <button type="submit">
        Save Production
    </button>

</form>

</body>
</html>