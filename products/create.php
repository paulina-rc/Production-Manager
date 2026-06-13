<?php

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

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
            LIMIT 1
        ");

        $stmt->execute([$name]);

        if ($stmt->fetch()) {

            $error = 'Product already exists';

        } else {

            $stmt = $pdo->prepare("
                INSERT INTO products (
                    name,
                    active
                )
                VALUES (?, 1)
            ");

            $stmt->execute([$name]);

            $success = 'Product created successfully';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>
</head>
<body>

    <h1>Create Product</h1>

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
            maxlength="150"
            required
        >

        <br><br>

        <button type="submit">
            Save Product
        </button>

    </form>

</body>
</html>