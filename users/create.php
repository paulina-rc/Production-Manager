<?php
require_once '../config/permissions.php';
requireAdmin();

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

$error = '';

$stmt = $pdo->query("
    SELECT id, role_name
    FROM roles
    ORDER BY role_name ASC
");

$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $roleId = (int) $_POST['role_id'];

    if (
        empty($fullName) ||
        empty($email) ||
        empty($password) ||
        empty($roleId)
    ) {

        $error = 'All fields are required';

    } else {

        $stmt = $pdo->prepare("
            SELECT id
            FROM users
            WHERE email = ?
            LIMIT 1
        ");

        $stmt->execute([$email]);

        if ($stmt->fetch()) {

            $error = 'Email already exists';

        } else {

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $pdo->prepare("
                INSERT INTO users (
                    full_name,
                    email,
                    password,
                    role_id,
                    status
                )
                VALUES (?, ?, ?, ?, 1)
            ");

            $stmt->execute([
                $fullName,
                $email,
                $hashedPassword,
                $roleId
            ]);

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
    <title>Create User</title>
</head>
<body>

<h1>Create User</h1>

<a href="list.php">Back to Users</a>

<br><br>

<?php if (!empty($error)): ?>
    <p><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST">

    <label>Full Name</label>
    <br>
    <input type="text" name="full_name" required>

    <br><br>

    <label>Email</label>
    <br>
    <input type="email" name="email" required>

    <br><br>

    <label>Password</label>
    <br>
    <input type="password" name="password" required>

    <br><br>

    <label>Role</label>
    <br>

    <select name="role_id" required>

        <option value="">
            Select a role
        </option>

        <?php foreach ($roles as $role): ?>

            <option value="<?php echo $role['id']; ?>">
                <?php echo htmlspecialchars($role['role_name']); ?>
            </option>

        <?php endforeach; ?>

    </select>

    <br><br>

    <button type="submit">
        Create User
    </button>

</form>

</body>
</html>