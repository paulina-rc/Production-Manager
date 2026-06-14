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