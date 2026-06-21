<?php

require_once '../config/permissions.php';
requireAdmin();

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

$error = '';

$stmt = $pdo->query("
    SELECT id, role_name
    FROM roles
    ORDER BY role_name ASC
");

$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $roleId = (int) $_POST['role_id'];

    if (
        empty($fullName) ||
        empty($email) ||
        empty($password) ||
        empty($roleId)
    ) {

        $error = 'Todos los campos son obligatorios';

    } else {

        $stmt = $pdo->prepare("
            SELECT id
            FROM users
            WHERE email = ?
            LIMIT 1
        ");

        $stmt->execute([$email]);

        if ($stmt->fetch()) {

            $error = 'El correo ya existe';

        } else {

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $pdo->prepare("
                INSERT INTO users (
                    full_name,
                    email,
                    password,
                    role_id,
                    status
                )
                VALUES (?, ?, ?, ?, 1)
            ");

            $stmt->execute([
                $fullName,
                $email,
                $hashedPassword,
                $roleId
            ]);

            header('Location: list.php');
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Nuevo Usuario</title>

    <link rel="stylesheet"
          <?php require_once '../includes/header.php'; ?>>

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

    <div class="welcome-box">

        <h1>Nuevo Usuario</h1>

        <p>
            Registrar un nuevo usuario en el sistema.
        </p>

    </div>

    <div class="form-card">

        <?php if (!empty($error)): ?>

            <div class="badge badge-danger">
                <?php echo htmlspecialchars($error); ?>
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
                    required
                >

            </div>

            <div class="form-group">

                <label>Correo Electrónico</label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    required
                >

            </div>

            <div class="form-group">

                <label>Contraseña</label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
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

                    <option value="">
                        Seleccione un rol
                    </option>

                    <?php foreach ($roles as $role): ?>

                        <option value="<?php echo $role['id']; ?>">
                            <?php echo htmlspecialchars($role['role_name']); ?>
                        </option>

                    <?php endforeach; ?>

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
                    Crear Usuario
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>

