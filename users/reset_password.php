<?php

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/permissions.php';
require_once dirname(__DIR__) . '/config/csrf.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

csrfCheck();

$userId = (int) ($_POST['id'] ?? 0);

if ($userId === (int) $_SESSION['user_id']) {
    header('Location: list.php?error=self');
    exit;
}

$stmt = $pdo->prepare("
    SELECT id, full_name
    FROM users
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$userId]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: list.php?error=notfound');
    exit;
}

function generarContraseniaTemporal($length)
{
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
    $max = strlen($chars) - 1;
    $password = '';

    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, $max)];
    }

    return $password;
}

$tempPassword = generarContraseniaTemporal(10);

$hashedPassword = password_hash(
    $tempPassword,
    PASSWORD_DEFAULT
);

$pdo->prepare("
    UPDATE users
    SET password = ?, must_change_password = 1
    WHERE id = ?
")->execute([
    $hashedPassword,
    $userId
]);

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Contraseña Restablecida</title>

    <?php require_once '../includes/header.php'; ?>

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

    <div class="welcome-box">

        <h1>Contraseña Restablecida</h1>

        <p>
            Contraseña temporal generada para
            <?php echo htmlspecialchars($user['full_name']); ?>.
        </p>

    </div>

    <div class="table-card">

        <div class="badge badge-danger">
            Esta contraseña no se volverá a mostrar. Copiala y entregala
            al usuario ahora.
        </div>

        <br><br>

        <p>
            <strong>Usuario:</strong>
            <?php echo htmlspecialchars($user['full_name']); ?>
        </p>

        <p>
            <strong>Contraseña temporal:</strong>
            <code><?php echo htmlspecialchars($tempPassword); ?></code>
        </p>

        <br>

        <a href="list.php" class="btn">
            Volver a Usuarios
        </a>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>
