
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

    <link rel="stylesheet"
          href="../assets/css/style.css">

</head>
<body style="background:#f4f6f9;">

<div style="
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
">

    <div class="form-card"
         style="
            width:450px;
            max-width:95%;
         ">

        <div style="text-align:center; margin-bottom:30px;">

            <h1 style="margin-bottom:10px;">
                Production Manager
            </h1>

            <p style="color:#666;">
                Sistema de Gestión de Producción Agroindustrial
            </p>

        </div>

        <?php if (!empty($error)): ?>

            <div class="badge badge-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>

            <br><br>

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

                <div style="position:relative;">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        required
                    >

                    <button
                        type="button"
                        onclick="togglePassword()"
                        style="
                            position:absolute;
                            right:10px;
                            top:50%;
                            transform:translateY(-50%);
                            border:none;
                            background:none;
                            cursor:pointer;
                            font-size:18px;
                        "
                    >
                        👁
                    </button>

                </div>

            </div>

            <button
                type="submit"
                class="btn"
                style="
                    width:100%;
                    margin-bottom:15px;
                "
            >
                Iniciar Sesión
            </button>

        </form>

        <div style="text-align:center;">

            <a href="forgot_password.php">
                ¿Olvidaste tu contraseña?
            </a>

        </div>

    </div>

</div>

<script>

function togglePassword() {

    const input =
        document.getElementById('password');

    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}

</script>

</body>
</html>

