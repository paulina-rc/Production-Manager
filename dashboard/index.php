<?php

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/permissions.php';

$totalProducts = $pdo->query("
    SELECT COUNT(*)
    FROM products
")->fetchColumn();

$totalUsers = $pdo->query("
    SELECT COUNT(*)
    FROM users
")->fetchColumn();

$totalSections = $pdo->query("
    SELECT COUNT(*)
    FROM sections
")->fetchColumn();

$totalProductions = $pdo->query("
    SELECT COUNT(*)
    FROM productions
    WHERE deleted_at IS NULL
")->fetchColumn();

$stmt = $pdo->query("
    SELECT
        productions.production_date,
        products.name AS product_name,
        sections.name AS section_name,
        productions.quantity,
        productions.unit
    FROM productions
    INNER JOIN products
        ON productions.product_id = products.id
    INNER JOIN sections
        ON productions.section_id = sections.id
    WHERE productions.deleted_at IS NULL
    ORDER BY productions.production_date DESC
    LIMIT 5
");

$recentProductions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$today = date('d/m/Y');

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Dashboard</title>

    <?php require_once '../includes/header.php'; ?>

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

    <div class="welcome-box">

        <h1>
            Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
        </h1>

        <div class="dashboard-date">
            Fecha: <?php echo $today; ?>
        </div>

    </div>

    <div class="stats-container">

        <div class="stat-card">

            <h3>Productos</h3>

            <div class="stat-number">
                <?php echo $totalProducts; ?>
            </div>

        </div>

        <div class="stat-card">

            <h3>Usuarios</h3>

            <div class="stat-number">
                <?php echo $totalUsers; ?>
            </div>

        </div>

        <div class="stat-card">

            <h3>Secciones</h3>

            <div class="stat-number">
                <?php echo $totalSections; ?>
            </div>

        </div>

        <div class="stat-card">

            <h3>Producciones</h3>

            <div class="stat-number">
                <?php echo $totalProductions; ?>
            </div>

        </div>

    </div>

    <div class="dashboard-grid">

        <div class="dashboard-card">

            <h2>
                Producciones Recientes
            </h2>

            <?php if (empty($recentProductions)): ?>

                <p>
                    No hay producciones registradas.
                </p>

            <?php else: ?>

                <table class="table">

                    <thead>

                        <tr>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Sección</th>
                            <th>Cantidad</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php foreach ($recentProductions as $production): ?>

                        <tr>

                            <td>
                                <?php echo htmlspecialchars($production['production_date']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($production['product_name']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($production['section_name']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($production['quantity']); ?>
                                <?php echo htmlspecialchars($production['unit']); ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            <?php endif; ?>

        </div>

        <div class="dashboard-card">

        </div>

    </div>

</div>

<div style="background:red;color:white;padding:30px;">
    PRUEBA ROJA
</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>

