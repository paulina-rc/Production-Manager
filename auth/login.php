<?php
session_start();
require_once '../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("
        SELECT *
        FROM users
        WHERE email = ?
        AND status = 1
        LIMIT 1
    ");

    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['role_id'] = $user['role_id'];

        header('Location: ../dashboard/index.php');
        exit;

    } else {

        $error = 'Correo o contraseña incorrectos';

    }
}

if (isset($_SESSION['user_id'])) {
    header('Location: ../dashboard/');
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Production Manager</title>

    <?php require_once '../includes/header.php'; ?>

</head>
<body>

<div class="login-page">

    <div class="login-card">

        <div class="login-header">

            <img src="../assets/img/logo.png"
                 alt="Logo"
                 class="login-logo">

            <h1>Production Manager</h1>

            <p>
                Sistema de Gestión de Producción Agroindustrial
            </p>

        </div>

        <?php if (!empty($error)): ?>

            <div class="badge badge-danger"
                 style="display:block; text-align:center; margin-bottom:20px;">
                <?php echo htmlspecialchars($error); ?>
            </div>

        <?php endif; ?>

        <form method="POST">

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

                <div class="password-wrapper">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        onclick="togglePassword()"
                    >
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>

                </div>

            </div>

            <button
                type="submit"
                class="btn"
                style="width:100%; margin-bottom:10px;"
            >
                Iniciar Sesión
            </button>

        </form>

        <div class="login-footer">

            <a href="forgot_password.php">
                ¿Olvidaste tu contraseña?
            </a>

        </div>

    </div>

</div>

<script>

function togglePassword() {

    const input = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

</script>

</body>
</html>
