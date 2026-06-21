```php
<?php

require_once '../config/permissions.php';
requireAdmin();

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);

    if (empty($name)) {

        $error = 'El nombre de la sección es obligatorio';

    } else {

        $stmt = $pdo->prepare("
            SELECT id
            FROM sections
            WHERE name = ?
            LIMIT 1
        ");

        $stmt->execute([$name]);

        if ($stmt->fetch()) {

            $error = 'La sección ya existe';

        } else {

            $stmt = $pdo->prepare("
                INSERT INTO sections (
                    name,
                    active
                )
                VALUES (?, 1)
            ");

            $stmt->execute([$name]);

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

    <title>Nueva Sección</title>

    <link rel="stylesheet"
          <?php require_once '../includes/header.php'; ?>>

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

    <div class="welcome-box">

        <h1>Nueva Sección</h1>

        <p>
            Registrar una nueva sección académica.
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

                <label>Nombre de la Sección</label>

                <input
                    type="text"
                    name="name"
                    maxlength="20"
                    class="form-control"
                    required
                >

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
                    Crear Sección
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>
```
