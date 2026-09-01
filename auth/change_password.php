<?php

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($password) || empty($confirmPassword)) {

        $error = 'Todos los campos son obligatorios.';

    } elseif ($password !== $confirmPassword) {

        $error = 'Las contraseñas no coinciden.';

    } elseif (strlen($password) < 8) {

        $error = 'La contraseña debe tener al menos 8 caracteres.';

    } else {

        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $pdo->prepare("
            UPDATE users
            SET password = ?, must_change_password = 0
            WHERE id = ?
        ")->execute([
            $hashedPassword,
            $_SESSION['user_id']
        ]);

        $_SESSION['must_change_password'] = 0;

        header('Location: ../dashboard/');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Cambiar Contraseña</title>

    <?php require_once '../includes/header.php'; ?>

</head>
<body>

<div class="main-content">

    <div class="welcome-box">

        <h1>Cambiar Contraseña</h1>

        <p>
            Debés establecer una nueva contraseña antes de continuar.
        </p>

    </div>

    <div class="table-card">

        <?php if (!empty($error)): ?>

            <div class="badge badge-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>

            <br><br>

        <?php endif; ?>

        <form method="POST">

            <div class="form-group">

                <label>Nueva Contraseña</label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    minlength="8"
                    required
                >

            </div>

            <div class="form-group">

                <label>Confirmar Contraseña</label>

                <input
                    type="password"
                    name="confirm_password"
                    class="form-control"
                    minlength="8"
                    required
                >

            </div>

            <button
                type="submit"
                class="btn"
            >
                Actualizar Contraseña
            </button>

        </form>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>
