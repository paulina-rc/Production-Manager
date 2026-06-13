<?php

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

$stmt = $pdo->query("
    SELECT
        productions.id,
        productions.production_date,
        products.name AS product_name,
        productions.raw_materials,
        productions.quantity,
        productions.unit,

        processor.full_name AS processed_by_name,

        creator.full_name AS created_by_name,

        sections.name AS section_name

    FROM productions

    INNER JOIN products
        ON productions.product_id = products.id

    INNER JOIN users processor
        ON productions.processed_by = processor.id

    INNER JOIN users creator
        ON productions.created_by = creator.id

    INNER JOIN sections
        ON productions.section_id = sections.id

    WHERE productions.deleted_at IS NULL

    ORDER BY productions.production_date DESC
");

$productions = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productions</title>
</head>
<body>

<h1>Production History</h1>

<a href="../dashboard/index.php">Dashboard</a>

<br><br>

<a href="create.php">New Production</a>

<br><br>

<table border="1">

    <tr>
        <th>Date</th>
        <th>Product</th>
        <th>Processed By</th>
        <th>Registered By</th>
        <th>Section</th>
        <th>Quantity</th>
        <th>Unit</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($productions as $production): ?>

        <tr>

            <td>
                <?php echo htmlspecialchars($production['production_date']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($production['product_name']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($production['processed_by_name']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($production['created_by_name']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($production['section_name']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($production['quantity']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($production['unit']); ?>
            </td>

            <td>

                <a href="view.php?id=<?php echo $production['id']; ?>">
                    View
                </a>

                |

                <a href="edit.php?id=<?php echo $production['id']; ?>">
                    Edit
                </a>

            </td>

        </tr>

    <?php endforeach; ?>

</table>

</body>
</html>