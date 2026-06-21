<?php

require_once '../config/permissions.php';
requireAdmin();

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

if (!isset($_GET['id'])) {
    header('Location: list.php');
    exit;
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("
    SELECT *
    FROM products
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: list.php');
    exit;
}

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
            AND id <> ?
            LIMIT 1
        ");

        $stmt->execute([$name, $id]);

        if ($stmt->fetch()) {

            $error = 'El producto ya existe';

        } else {

            $stmt = $pdo->prepare("
                UPDATE products
                SET name = ?
                WHERE id = ?
            ");

            $stmt->execute([$name, $id]);

            $success = 'Producto actualizado correctamente';

            $stmt = $pdo->prepare("
                SELECT *
                FROM products
                WHERE id = ?
            ");

            $stmt->execute([$id]);

            $product = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Editar Producto</title>

    <link rel="stylesheet"
          <?php require_once '../includes/header.php'; ?>>

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

    <div class="welcome-box">

        <h1>Editar Producto</h1>

        <p>
            Modificar la información del producto.
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
                    class="form-control"
                    value="<?php echo htmlspecialchars($product['name']); ?>"
                    maxlength="150"
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
                    Guardar Cambios
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>

