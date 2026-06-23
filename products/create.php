<?php

require_once '../config/permissions.php';
requireAdmin();

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);

    if (empty($name)) {

        $error = 'El nombre del producto es obligatorio';

    } else {

        $stmt = $pdo->prepare("
            SELECT id
            FROM products
            WHERE name = ?
            LIMIT 1
        ");

        $stmt->execute([$name]);

        if ($stmt->fetch()) {

            $error = 'El producto ya existe';

        } else {

            $stmt = $pdo->prepare("
                INSERT INTO products (
                    name,
                    active
                )
                VALUES (?, 1)
            ");

            $stmt->execute([$name]);

            $success = 'Producto creado correctamente';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Nuevo Producto</title>

    <link rel="stylesheet"
          <?php require_once '../includes/header.php'; ?>>

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

    <div class="welcome-box">

        <h1>Nuevo Producto</h1>

        <p>
            Registrar un nuevo producto en el sistema.
        </p>

    </div>

    <div class="form-card">

        <?php if (!empty($error)): ?>

            <div class="badge badge-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>

            <br><br>

        <?php endif; ?>

        <?php if (!empty($success)): ?>

            <div class="badge badge-success">
                <?php echo htmlspecialchars($success); ?>
            </div>

            <br><br>

        <?php endif; ?>

        <form method="POST">

            <div class="form-group">

                <label>Nombre del Producto</label>

                <input
                    type="text"
                    name="name"
                    maxlength="150"
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
                    class="btn">
                    Guardar Producto
                </button>

            </div>

        </form>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>

