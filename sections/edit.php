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

        $error = 'Section name is required';

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

            $error = 'Section already exists';

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

            $success = 'Section updated successfully';

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Section</title>
</head>
<body>

<h1>Edit Section</h1>

<a href="list.php">Back to Sections</a>

<br><br>

<?php if (!empty($error)): ?>
    <p><?php echo $error; ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p><?php echo $success; ?></p>
<?php endif; ?>

<form method="POST">

    <label>Section Name</label>

    <br>

    <input
        type="text"
        name="name"
        value="<?php echo htmlspecialchars($section['name']); ?>"
        required
    >

    <br><br>

    <label>Status</label>

    <br>

    <select name="active">

        <option value="1"
            <?php echo ($section['active'] == 1) ? 'selected' : ''; ?>>
            Active
        </option>

        <option value="0"
            <?php echo ($section['active'] == 0) ? 'selected' : ''; ?>>
            Inactive
        </option>

    </select>

    <br><br>

    <button type="submit">
        Save Changes
    </button>

</form>

</body>
</html>