<?php
require_once '../config/permissions.php';
requireAdmin();

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

if (!isset($_GET['id'])) {
    header('Location: list.php');
    exit;
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("
    SELECT *
    FROM products
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: list.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);

    if (empty($name)) {

        $error = 'Product name is required';

    } else {

        $stmt = $pdo->prepare("
            SELECT id
            FROM products
            WHERE name = ?
            AND id <> ?
            LIMIT 1
        ");

        $stmt->execute([$name, $id]);

        if ($stmt->fetch()) {

            $error = 'Product already exists';

        } else {

            $stmt = $pdo->prepare("
                UPDATE products
                SET name = ?
                WHERE id = ?
            ");

            $stmt->execute([$name, $id]);

            $success = 'Product updated successfully';

            $stmt = $pdo->prepare("
                SELECT *
                FROM products
                WHERE id = ?
            ");

            $stmt->execute([$id]);

            $product = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
</head>
<body>

<h1>Edit Product</h1>

<a href="list.php">Back to Products</a>

<br><br>

<?php if (!empty($error)): ?>
    <p><?php echo $error; ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p><?php echo $success; ?></p>
<?php endif; ?>

<form method="POST">

    <label>Product Name</label>

    <br>

    <input
        type="text"
        name="name"
        value="<?php echo htmlspecialchars($product['name']); ?>"
        required
    >

    <br><br>

    <button type="submit">
        Save Changes
    </button>

</form>

</body>
</html>