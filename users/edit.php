```php
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
    FROM users
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->query("
    SELECT id, role_name
    FROM roles
    ORDER BY role_name ASC
");

$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $roleId = (int) $_POST['role_id'];
    $status = (int) $_POST['status'];

    if (
        empty($fullName) ||
        empty($email) ||
        empty($roleId)
    ) {

        $error = 'Todos los campos son obligatorios';

    } else {

        $stmt = $pdo->prepare("
            SELECT id
            FROM users
            WHERE email = ?
            AND id <> ?
            LIMIT 1
        ");

        $stmt->execute([$email, $id]);

        if ($stmt->fetch()) {

            $error = 'El correo ya existe';

        } else {

            $stmt = $pdo->prepare("
                UPDATE users
                SET
                    full_name = ?,
                    email = ?,
                    role_id = ?,
                    status = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $fullName,
                $email,
                $roleId,
                $status,
                $id
            ]);

            $success = 'Usuario actualizado correctamente';

            $stmt = $pdo->prepare("
                SELECT *
                FROM users
                WHERE id = ?
            ");

            $stmt->execute([$id]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Editar Usuario</title>

    <link rel="stylesheet"
          HEADER_REPLACED>

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

    <div class="welcome-box">

        <h1>Editar Usuario</h1>

        <p>
            Modificar información y permisos del usuario.
        </p>

    </div>

    <div class="form-card">

        <?php if (!empty($error)): ?>

            <div class="badge badge-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>

            <br><br>

        <?php endif; ?>

        <?php if (!empty($success)): ?>

            <div class="badge badge-success">
                <?php echo htmlspecialchars($success); ?>
            </div>

            <br><br>

        <?php endif; ?>

        <form method="POST">

            <div class="form-group">

                <label>Nombre Completo</label>

                <input
                    type="text"
                    name="full_name"
                    class="form-control"
                    value="<?php echo htmlspecialchars($user['full_name']); ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>Correo Electrónico</label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="<?php echo htmlspecialchars($user['email']); ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>Rol</label>

                <select
                    name="role_id"
                    class="form-control"
                    required
                >

                    <?php foreach ($roles as $role): ?>

                        <option
                            value="<?php echo $role['id']; ?>"
                            <?php echo ($role['id'] == $user['role_id']) ? 'selected' : ''; ?>
                        >
                            <?php echo htmlspecialchars($role['role_name']); ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="form-group">

                <label>Estado</label>

                <select
                    name="status"
                    class="form-control"
                >

                    <option value="1"
                        <?php echo ($user['status'] == 1) ? 'selected' : ''; ?>
                    >
                        Activo
                    </option>

                    <option value="0"
                        <?php echo ($user['status'] == 0) ? 'selected' : ''; ?>
                    >
                        Inactivo
                    </option>

                </select>

            </div>

            <div class="page-header-actions">

                <a href="list.php"
                   class="btn btn-secondary">
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="btn"
                >
                    Guardar Cambios
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>
```
