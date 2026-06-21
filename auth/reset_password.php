```php
<?php

require_once dirname(__DIR__) . '/config/database.php';

$message = '';
$error = '';

$token = $_GET['token'] ?? '';

if (empty($token)) {
    die('Token inválido.');
}

$stmt = $pdo->prepare("
    SELECT *
    FROM users
    WHERE reset_token = ?
    LIMIT 1
");

$stmt->execute([$token]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('Token inválido.');
}

if (
    empty($user['reset_token_expires']) ||
    strtotime($user['reset_token_expires']) < time()
) {
    die('El enlace ha expirado.');
}

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

        $updateStmt = $pdo->prepare("
            UPDATE users
            SET
                password = ?,
                reset_token = NULL,
                reset_token_expires = NULL
            WHERE id = ?
        ");

        $updateStmt->execute([
            $hashedPassword,
            $user['id']
        ]);

        header(
            'Location: login.php?reset=success'
        );
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Restablecer Contraseña</title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

</head>
<body>

<div class="main-content">

    <div class="welcome-box">

        <h1>Restablecer Contraseña</h1>

        <p>
            Ingresa tu nueva contraseña.
        </p>

    </div>

    <div class="table-card">

        <?php if (!empty($error)): ?>

            <p>
                <?php echo htmlspecialchars($error); ?>
            </p>

        <?php endif; ?>

        <form method="POST">

            <label>Nueva Contraseña</label>

            <input
                type="password"
                name="password"
                required
            >

            <br><br>

            <label>Confirmar Contraseña</label>

            <input
                type="password"
                name="confirm_password"
                required
            >

            <br><br>

            <button
                type="submit"
                class="btn"
            >
                Actualizar Contraseña
            </button>

        </form>

    </div>

</div>

</body>
</html>
```
