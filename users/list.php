<?php
require_once '../config/permissions.php';
requireAdmin();

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

$stmt = $pdo->query("
    SELECT
        users.id,
        users.full_name,
        users.email,
        users.status,
        roles.role_name
    FROM users
    INNER JOIN roles
        ON users.role_id = roles.id
    ORDER BY users.full_name ASC
");

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
</head>
<body>

<h1>Users</h1>
<a href="create.php">Create User</a>

<br><br>

<a href="../dashboard/index.php">Dashboard</a>

<br><br>

<table border="1">

    <tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($users as $user): ?>

        <tr>

            <td><?php echo $user['id']; ?></td>

            <td><?php echo htmlspecialchars($user['full_name']); ?></td>

            <td><?php echo htmlspecialchars($user['email']); ?></td>

            <td><?php echo htmlspecialchars($user['role_name']); ?></td>

            <td>
                <?php echo $user['status'] ? 'Active' : 'Inactive'; ?>
            </td>

            <td>
                <a href="edit.php?id=<?php echo $user['id']; ?>">
                    Edit
                </a>
            </td>

        </tr>

    <?php endforeach; ?>

</table>

</body>
</html>