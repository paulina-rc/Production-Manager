<?php

require_once '../config/permissions.php';
requireAdmin();

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/csrf.php';

$perPage = 20;

$page = (int) ($_GET['page'] ?? 1);
if ($page < 1) {
    $page = 1;
}

$totalUsers = (int) $pdo->query("
    SELECT COUNT(*)
    FROM users
    INNER JOIN roles
        ON users.role_id = roles.id
")->fetchColumn();

$totalPages = $totalUsers > 0
    ? (int) ceil($totalUsers / $perPage)
    : 0;

if ($totalPages > 0 && $page > $totalPages) {
    $page = $totalPages;
}

$offset = ($page - 1) * $perPage;

$stmt = $pdo->prepare("
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
    LIMIT :limit OFFSET :offset
");

$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

function buildPageUrl($page)
{
    $params = $_GET;
    $params['page'] = $page;

    return '?' . http_build_query($params);
}

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
                <?php echo $totalUsers; ?>
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

        <?php if (empty($users)): ?>

            <p>No se encontraron usuarios.</p>

        <?php else: ?>

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

                    <td data-label="ID">
                        <?php echo $user['id']; ?>
                    </td>

                    <td data-label="Nombre Completo">
                        <?php echo htmlspecialchars($user['full_name']); ?>
                    </td>

                    <td data-label="Correo">
                        <?php echo htmlspecialchars($user['email']); ?>
                    </td>

                    <td data-label="Rol">
                        <?php echo htmlspecialchars($user['role_name']); ?>
                    </td>

                    <td data-label="Estado">
                        <?php echo $user['status'] ? 'Activo' : 'Inactivo'; ?>
                    </td>

                    <td class="action-links" data-label="Acciones">

                        <a href="edit.php?id=<?php echo $user['id']; ?>">
                            Editar
                        </a>

                        <?php if ((int) $user['id'] !== (int) $_SESSION['user_id']): ?>

                            <form method="POST"
                                  action="reset_password.php"
                                  onsubmit="return confirm('¿Restablecer la contraseña de este usuario? Se generará una contraseña temporal.');">

                                <input type="hidden" name="id" value="<?php echo (int) $user['id']; ?>">

                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrfToken()); ?>">

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

