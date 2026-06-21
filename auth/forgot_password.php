<?php

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/mail.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("
        SELECT *
        FROM users
        WHERE email = ?
        LIMIT 1
    ");

    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        $token = bin2hex(random_bytes(32));

        $expires = date(
            'Y-m-d H:i:s',
            strtotime('+1 hour')
        );

        $updateStmt = $pdo->prepare("
            UPDATE users
            SET
                reset_token = ?,
                reset_token_expires = ?
            WHERE id = ?
        ");

        $updateStmt->execute([
            $token,
            $expires,
            $user['id']
        ]);

        try {

            $env = parse_ini_file(
                dirname(__DIR__) . '/.env'
            );

            $resetLink =
                $env['APP_URL']
                . '/auth/reset_password.php?token='
                . $token;

            $mail = getMailer();

            $mail->addAddress($email);

            $mail->Subject =
                'Recuperación de contraseña';

            $mail->isHTML(true);

            $mail->Body = "
                <h2>Recuperación de contraseña</h2>

                <p>
                    Hemos recibido una solicitud para
                    restablecer tu contraseña.
                </p>

                <p>
                    Haz clic en el siguiente enlace:
                </p>

                <p>
                    <a href='{$resetLink}'>
                        Restablecer contraseña
                    </a>
                </p>

                <p>
                    Este enlace expirará en 1 hora.
                </p>
            ";

            $mail->send();

            $message =
                'Se ha enviado un enlace de recuperación a tu correo.';

        } catch (Exception $e) {

            $error = $e->getMessage();
        }

    } else {

        $error = 'No existe una cuenta con ese correo.';
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Recuperar Contraseña</title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

</head>
<body>

<div class="main-content">

    <div class="welcome-box">

        <h1>Recuperar Contraseña</h1>

        <p>
            Ingresa tu correo para recibir un enlace de recuperación.
        </p>

    </div>

    <div class="table-card">

        <?php if (!empty($message)): ?>

            <p>
                <?php echo htmlspecialchars($message); ?>
            </p>

        <?php endif; ?>

        <?php if (!empty($error)): ?>

            <p>
                <?php echo htmlspecialchars($error); ?>
            </p>

        <?php endif; ?>

        <form method="POST">

            <label>Correo electrónico</label>

            <input
                type="email"
                name="email"
                required
            >

            <br><br>

            <button
                type="submit"
                class="btn"
            >
                Enviar enlace
            </button>

        </form>

    </div>

</div>

</body>
</html>

