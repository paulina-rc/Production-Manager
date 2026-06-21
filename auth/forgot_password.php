<?php

require_once dirname(__DIR__) . '/config/mail.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $mail = getMailer();

        $mail->addAddress($_POST['email']);

        $mail->Subject = 'Prueba de correo';

        $mail->Body = '
            Este es un correo de prueba enviado desde Production Manager.
        ';

        $mail->send();

        $message = 'Correo enviado correctamente.';

    } catch (Exception $e) {

        $error = $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Prueba de Correo</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="main-content">

    <div class="welcome-box">

        <h1>Prueba de Correo</h1>

        <p>
            Verificar configuración SMTP.
        </p>

    </div>

    <div class="table-card">

        <?php if (!empty($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST">

            <label>Correo destino</label>

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
                Enviar prueba
            </button>

        </form>

    </div>

</div>

</body>
</html>
```
