<?php

require_once '../config/permissions.php';
requireAdmin();

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

$stmt = $pdo->query("
    SELECT *
    FROM sections
    ORDER BY name ASC
");

$sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Secciones</title>

    <link rel="stylesheet"
          <?php require_once '../includes/header.php'; ?>>

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

    <div class="welcome-box">

        <h1>Secciones</h1>

        <p>
            Administración de secciones académicas.
        </p>

    </div>

    <div class="stats-container">

        <div class="stat-card">

            <h3>Total Secciones</h3>

            <div class="stat-number">
                <?php echo count($sections); ?>
            </div>

        </div>

    </div>

    <div class="table-card">

        <div class="table-header">

            <h2>Lista de Secciones</h2>

            <a class="btn"
               href="create.php">
                + Nueva Sección
            </a>

        </div>

        <table class="table">

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Sección</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

            <?php foreach ($sections as $section): ?>

                <tr>

                    <td>
                        <?php echo $section['id']; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($section['name']); ?>
                    </td>

                    <td>

                        <?php if ($section['active']): ?>

                            <span class="badge badge-success">
                                Activa
                            </span>

                        <?php else: ?>

                            <span class="badge badge-danger">
                                Inactiva
                            </span>

                        <?php endif; ?>

                    </td>

                    <td class="action-links">

                        <a href="edit.php?id=<?php echo $section['id']; ?>">
                            Editar
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>

