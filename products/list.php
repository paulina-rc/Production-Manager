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
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Productos</title>

    <link rel="stylesheet"
          href="../assets/css/style.css">

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

    <div class="welcome-box">

        <h1>Productos</h1>

        <p>
            Administración de productos registrados en el sistema.
        </p>

    </div>

    <div class="stats-container">

        <div class="stat-card">

            <h3>Total Productos</h3>

            <div class="stat-number">
                <?php echo count($products); ?>
            </div>

        </div>

    </div>

    <div class="table-card">

        <div class="table-header">

            <h2>Lista de Productos</h2>

            <a class="btn"
               href="create.php">
                + Nuevo Producto
            </a>

        </div>

        <table class="table">

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

            <?php foreach ($products as $product): ?>

                <tr>

                    <td>
                        <?php echo $product['id']; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($product['name']); ?>
                    </td>

                    <td>

                        <?php if ($product['active']): ?>

                            <span class="badge badge-success">
                                Activo
                            </span>

                        <?php else: ?>

                            <span class="badge badge-danger">
                                Inactivo
                            </span>

                        <?php endif; ?>

                    </td>

                    <td class="action-links">

                        <a href="edit.php?id=<?php echo $product['id']; ?>">
                            Editar
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>
