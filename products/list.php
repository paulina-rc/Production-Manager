<?php
require_once '../config/permissions.php';
requireAdmin();

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

$stmt = $pdo->query("
    SELECT *
    FROM products
    ORDER BY name ASC
");

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
</head>
<body>

    <h1>Products</h1>
    <a href="create.php">Create Product</a>

    <br><br>

    <a href="../dashboard/index.php">Dashboard</a>

    <br><br>

    <table border="1">

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($products as $product): ?>

            <tr>

                <td><?php echo $product['id']; ?></td>

                <td><?php echo htmlspecialchars($product['name']); ?></td>

                <td>
                    <?php echo $product['active'] ? 'Active' : 'Inactive'; ?>
                </td>

                <td>
                    <a href="edit.php?id=<?php echo $product['id']; ?>">
                        Edit
                    </a>
                </td>

            </tr>

        <?php endforeach; ?>

    </table>

</body>
</html>