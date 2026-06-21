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
    FROM sections
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$id]);

$section = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$section) {
    header('Location: list.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $active = (int) $_POST['active'];

    if (empty($name)) {

        $error = 'El nombre de la sección es obligatorio';

    } else {

        $stmt = $pdo->prepare("
            SELECT id
            FROM sections
            WHERE name = ?
            AND id <> ?
            LIMIT 1
        ");

        $stmt->execute([$name, $id]);

        if ($stmt->fetch()) {

            $error = 'La sección ya existe';

        } else {

            $stmt = $pdo->prepare("
                UPDATE sections
                SET
                    name = ?,
                    active = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $name,
                $active,
                $id
            ]);

            $success = 'Sección actualizada correctamente';

            $stmt = $pdo->prepare("
                SELECT *
                FROM sections
                WHERE id = ?
            ");

            $stmt->execute([$id]);

            $section = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Editar Sección</title>

    <link rel="stylesheet"
          href="../assets/css/style.css">

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

    <div class="welcome-box">

        <h1>Editar Sección</h1>

        <p>
            Modificar la información de una sección.
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

                <label>Nombre de la Sección</label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="<?php echo htmlspecialchars($section['name']); ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>Estado</label>

                <select
                    name="active"
                    class="form-control"
                >

                    <option value="1"
                        <?php echo ($section['active'] == 1) ? 'selected' : ''; ?>>
                        Activa
                    </option>

                    <option value="0"
                        <?php echo ($section['active'] == 0) ? 'selected' : ''; ?>>
                        Inactiva
                    </option>

                </select>

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

