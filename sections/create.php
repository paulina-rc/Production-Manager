<?php
require_once '../config/permissions.php';
requireAdmin();

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);

    if (empty($name)) {

        $error = 'Section name is required';

    } else {

        $stmt = $pdo->prepare("
            SELECT id
            FROM sections
            WHERE name = ?
            LIMIT 1
        ");

        $stmt->execute([$name]);

        if ($stmt->fetch()) {

            $error = 'Section already exists';

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Section</title>
</head>
<body>

<h1>Create Section</h1>

<a href="list.php">Back to Sections</a>

<br><br>

<?php if (!empty($error)): ?>
    <p><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST">

    <label>Section Name</label>

    <br>

    <input
        type="text"
        name="name"
        maxlength="20"
        required
    >

    <br><br>

    <button type="submit">
        Create Section
    </button>

</form>

</body>
</html>