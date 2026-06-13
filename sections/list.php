<?php

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

$stmt = $pdo->query("
    SELECT *
    FROM sections
    ORDER BY name ASC
");

$sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sections</title>
</head>
<body>

<h1>Sections</h1>

<a href="../dashboard/index.php">Dashboard</a>

<br><br>

<a href="create.php">Create Section</a>

<br><br>

<table border="1">

    <tr>
        <th>ID</th>
        <th>Section</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($sections as $section): ?>

        <tr>

            <td><?php echo $section['id']; ?></td>

            <td><?php echo htmlspecialchars($section['name']); ?></td>

            <td>
                <?php echo $section['active'] ? 'Active' : 'Inactive'; ?>
            </td>

            <td>
                <a href="edit.php?id=<?php echo $section['id']; ?>">
                    Edit
                </a>
            </td>

        </tr>

    <?php endforeach; ?>

</table>

</body>
</html>