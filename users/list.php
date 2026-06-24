<?php

require_once '../config/permissions.php';
requireAdmin();

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

$stmt = $pdo->query("
    SELECT
        users.id,
        users.full_name,
        users.email,
        users.status,
        roles.role_name
    FROM users
    INNER JOIN roles
        ON users.role_id = roles.id
    ORDER BY users.full_name ASC
");

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Usuarios</title>

    <?php require_once '../includes/header.php'; ?>

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

    <div class="welcome-box">

        <h1>Usuarios</h1>

        <p>
            Administración de usuarios y permisos del sistema.
        </p>

    </div>

    <div class="stats-container">

        <div class="stat-card">

            <h3>Total Usuarios</h3>

            <div class="stat-number">
                <?php echo count($users); ?>
            </div>

        </div>

    </div>

    <div class="table-card">

        <div class="table-header">

            <h2>Lista de Usuarios</h2>

            <a class="btn"
               href="create.php">
                + Nuevo Usuario
            </a>

        </div>

        <table class="table">

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

            <?php foreach ($users as $user): ?>

                <tr>

                    <td>
                        <?php echo $user['id']; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($user['full_name']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($user['email']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($user['role_name']); ?>
                    </td>

                    <td>
                        <?php echo $user['status'] ? 'Activo' : 'Inactivo'; ?>
                    </td>

                    <td class="action-links">

                        <a href="edit.php?id=<?php echo $user['id']; ?>">
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

