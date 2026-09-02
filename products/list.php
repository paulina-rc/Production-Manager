<?php

require_once '../config/permissions.php';
requireAdmin();

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

$perPage = 20;

$page = (int) ($_GET['page'] ?? 1);
if ($page < 1) {
    $page = 1;
}

$totalProducts = (int) $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();

$totalPages = $totalProducts > 0
    ? (int) ceil($totalProducts / $perPage)
    : 0;

if ($totalPages > 0 && $page > $totalPages) {
    $page = $totalPages;
}

$offset = ($page - 1) * $perPage;

$stmt = $pdo->prepare("
    SELECT *
    FROM products
    ORDER BY name ASC
    LIMIT ? OFFSET ?
");

$stmt->execute([$perPage, $offset]);

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

function buildPageUrl($page)
{
    $params = $_GET;
    $params['page'] = $page;

    return '?' . http_build_query($params);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Productos</title>

    <?php require_once '../includes/header.php'; ?>

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
                <?php echo $totalProducts; ?>
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

        <?php if (empty($products)): ?>

            <p>No se encontraron productos.</p>

        <?php else: ?>

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

        <div class="pagination">

            <?php if ($page > 1): ?>
                <a href="<?php echo htmlspecialchars(buildPageUrl($page - 1)); ?>" class="btn btn-sm btn-secondary">
                    Anterior
                </a>
            <?php else: ?>
                <span class="btn btn-sm btn-secondary pagination-disabled">
                    Anterior
                </span>
            <?php endif; ?>

            <span class="pagination-status">
                Página <?php echo $page; ?> de <?php echo $totalPages; ?>
            </span>

            <?php if ($page < $totalPages): ?>
                <a href="<?php echo htmlspecialchars(buildPageUrl($page + 1)); ?>" class="btn btn-sm btn-secondary">
                    Siguiente
                </a>
            <?php else: ?>
                <span class="btn btn-sm btn-secondary pagination-disabled">
                    Siguiente
                </span>
            <?php endif; ?>

        </div>

        <?php endif; ?>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>
