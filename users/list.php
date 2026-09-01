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

$error = '';

if (($_GET['error'] ?? '') === 'self') {
    $error = 'No podés restablecer tu propia contraseña desde aquí.';
} elseif (($_GET['error'] ?? '') === 'notfound') {
    $error = 'Usuario no encontrado.';
}

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

    <?php if (!empty($error)): ?>

        <div class="badge badge-danger">
            <?php echo htmlspecialchars($error); ?>
        </div>

        <br><br>

    <?php endif; ?>

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

                        <?php if ((int) $user['id'] !== (int) $_SESSION['user_id']): ?>

                            <form method="POST"
                                  action="reset_password.php"
                                  onsubmit="return confirm('¿Restablecer la contraseña de este usuario? Se generará una contraseña temporal.');">

                                <input type="hidden" name="id" value="<?php echo (int) $user['id']; ?>">

                                <button type="submit">
                                    Restablecer contraseña
                                </button>

                            </form>

                        <?php endif; ?>

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

