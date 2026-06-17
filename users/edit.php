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
    FROM users
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->query("
    SELECT id, role_name
    FROM roles
    ORDER BY role_name ASC
");

$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $roleId = (int) $_POST['role_id'];
    $status = (int) $_POST['status'];

    if (
        empty($fullName) ||
        empty($email) ||
        empty($roleId)
    ) {

        $error = 'All fields are required';

    } else {

        $stmt = $pdo->prepare("
            SELECT id
            FROM users
            WHERE email = ?
            AND id <> ?
            LIMIT 1
        ");

        $stmt->execute([$email, $id]);

        if ($stmt->fetch()) {

            $error = 'Email already exists';

        } else {

            $stmt = $pdo->prepare("
                UPDATE users
                SET
                    full_name = ?,
                    email = ?,
                    role_id = ?,
                    status = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $fullName,
                $email,
                $roleId,
                $status,
                $id
            ]);

            $success = 'User updated successfully';

            $stmt = $pdo->prepare("
                SELECT *
                FROM users
                WHERE id = ?
            ");

            $stmt->execute([$id]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>

<h1>Edit User</h1>

<a href="list.php">Back to Users</a>

<br><br>

<?php if (!empty($error)): ?>
    <p><?php echo $error; ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p><?php echo $success; ?></p>
<?php endif; ?>

<form method="POST">

    <label>Full Name</label>
    <br>
    <input
        type="text"
        name="full_name"
        value="<?php echo htmlspecialchars($user['full_name']); ?>"
        required
    >

    <br><br>

    <label>Email</label>
    <br>
    <input
        type="email"
        name="email"
        value="<?php echo htmlspecialchars($user['email']); ?>"
        required
    >

    <br><br>

    <label>Role</label>
    <br>

    <select name="role_id" required>

        <?php foreach ($roles as $role): ?>

            <option
                value="<?php echo $role['id']; ?>"
                <?php echo ($role['id'] == $user['role_id']) ? 'selected' : ''; ?>
            >
                <?php echo htmlspecialchars($role['role_name']); ?>
            </option>

        <?php endforeach; ?>

    </select>

    <br><br>

    <label>Status</label>
    <br>

    <select name="status">

        <option value="1"
            <?php echo ($user['status'] == 1) ? 'selected' : ''; ?>
        >
            Active
        </option>

        <option value="0"
            <?php echo ($user['status'] == 0) ? 'selected' : ''; ?>
        >
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