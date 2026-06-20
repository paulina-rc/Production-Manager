<?php

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

$stmt = $pdo->prepare("
    SELECT
        users.*,
        roles.role_name AS role_name
    FROM users
    INNER JOIN roles
        ON users.role_id = roles.id
    WHERE users.id = ?
    LIMIT 1
");

$stmt->execute([
    $_SESSION['user_id']
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: ../dashboard/index.php');
    exit;
}

$message = '';
$error = '';

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['change_password'])
) {

    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (
        empty($currentPassword)
        || empty($newPassword)
        || empty($confirmPassword)
    ) {

        $error = 'Todos los campos son obligatorios.';

    } elseif (!password_verify($currentPassword, $user['password'])) {

        $error = 'La contraseña actual es incorrecta.';

    } elseif ($newPassword !== $confirmPassword) {

        $error = 'Las nuevas contraseñas no coinciden.';

    } elseif (strlen($newPassword) < 8) {

        $error = 'La nueva contraseña debe tener al menos 8 caracteres.';

    } else {

        $newHash = password_hash(
            $newPassword,
            PASSWORD_DEFAULT
        );

        $updateStmt = $pdo->prepare("
            UPDATE users
            SET password = ?
            WHERE id = ?
        ");

        $updateStmt->execute([
            $newHash,
            $user['id']
        ]);

        $message = 'Contraseña actualizada correctamente.';
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <title>Mi Perfil</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

    <div class="welcome-box">
        <h1>Mi Perfil</h1>
        <p>Información de la cuenta actual.</p>
    </div>

    <div class="profile-container">

        <!-- INFORMACIÓN PERSONAL -->
        <div class="profile-card">

            <h2>Información Personal</h2>

            <hr>

            <h3>Nombre</h3>
            <p><?php echo htmlspecialchars($user['full_name']); ?></p>

            <hr>

            <h3>Correo</h3>
            <p><?php echo htmlspecialchars($user['email']); ?></p>

            <hr>

            <h3>Rol</h3>
            <p><?php echo htmlspecialchars($user['role_name']); ?></p>

            <hr>

            <h3>Último acceso</h3>

            <p>
                <?php
                echo !empty($user['last_login'])
                    ? htmlspecialchars($user['last_login'])
                    : 'Sin registros';
                ?>
            </p>

        </div>

        <!-- CONFIGURACIÓN -->
        <div class="profile-card">

            <h2>Configuración de Cuenta</h2>

            <hr>

            <?php if (!empty($message)): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <h3>Cambiar Contraseña</h3>

            <form method="POST">

                <label>Contraseña Actual</label>

                <input
                    type="password"
                    name="current_password"
                >

                <br><br>

                <label>Nueva Contraseña</label>

                <input
                    type="password"
                    name="new_password"
                >

                <br><br>

                <label>Confirmar Nueva Contraseña</label>

                <input
                    type="password"
                    name="confirm_password"
                >

                <br><br>
                
                <button
                    type="submit"
                    name="change_password"
                    class="btn"
                >
                    Actualizar Contraseña
                </button>

            </form>

            <hr>

            <h3>Idioma</h3>
            <p>Próximamente</p>

            <hr>

            <h3>Tema</h3>
            <p>Próximamente</p>

        </div>

    </div>

</div>

</body>
</html>