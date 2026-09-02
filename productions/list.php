<?php

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

$search = trim($_GET['search'] ?? '');

$perPage = 20;

$page = (int) ($_GET['page'] ?? 1);
if ($page < 1) {
    $page = 1;
}

$joinsSql = "
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
";

if (!empty($search)) {

    $joinsSql .= "
        AND (
            products.name LIKE :search1
            OR processor.full_name LIKE :search2
            OR creator.full_name LIKE :search3
            OR sections.name LIKE :search4
        )
    ";

    $searchTerm = '%' . $search . '%';
}

$countStmt = $pdo->prepare("SELECT COUNT(*) {$joinsSql}");

if (!empty($search)) {
    $countStmt->bindValue(':search1', $searchTerm, PDO::PARAM_STR);
    $countStmt->bindValue(':search2', $searchTerm, PDO::PARAM_STR);
    $countStmt->bindValue(':search3', $searchTerm, PDO::PARAM_STR);
    $countStmt->bindValue(':search4', $searchTerm, PDO::PARAM_STR);
}

$countStmt->execute();

$totalProductions = (int) $countStmt->fetchColumn();

$totalPages = $totalProductions > 0
    ? (int) ceil($totalProductions / $perPage)
    : 0;

if ($totalPages > 0 && $page > $totalPages) {
    $page = $totalPages;
}

$offset = ($page - 1) * $perPage;

$sql = "
    SELECT
        productions.id,
        productions.production_date,
        products.name AS product_name,
        productions.quantity,
        productions.unit,

        processor.full_name AS processed_by_name,

        creator.full_name AS created_by_name,

        sections.name AS section_name

    {$joinsSql}
    ORDER BY productions.production_date DESC
    LIMIT :limit OFFSET :offset
";

$stmt = $pdo->prepare($sql);

if (!empty($search)) {
    $stmt->bindValue(':search1', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':search2', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':search3', $searchTerm, PDO::PARAM_STR);
    $stmt->bindValue(':search4', $searchTerm, PDO::PARAM_STR);
}

$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$productions = $stmt->fetchAll(PDO::FETCH_ASSOC);

function buildPageUrl($page)
{
    $params = $_GET;
    $params['page'] = $page;

    return '?' . http_build_query($params);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Producciones</title>

    <?php require_once '../includes/header.php'; ?>

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

    <div class="welcome-box">

        <h1>Producciones</h1>

        <p>
            Historial completo de producciones registradas.
        </p>

    </div>

    <div class="stats-container">

        <div class="stat-card">
            <h3>Total Producciones</h3>
            <div class="stat-number">
                <?php echo $totalProductions; ?>
            </div>
        </div>

    </div>

    <div class="table-card">

        <div class="table-header">

            <h2>Historial de Producción</h2>

            <a class="btn"
               href="create.php">
                + Nueva Producción
            </a>

        </div>

        <form method="GET">

            <input
                type="text"
                name="search"
                placeholder="Buscar producto, usuario o sección..."
                value="<?php echo htmlspecialchars($search); ?>"
                class="form-control"
            >

            <button
                type="submit"
                class="btn"
            >
                Buscar
            </button>

        </form>

        <?php if (empty($productions)): ?>

            <p>No se encontraron producciones.</p>

        <?php else: ?>

        <table class="table">

            <thead>

                <tr>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Procesado Por</th>
                    <th>Registrado Por</th>
                    <th>Sección</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

            <?php foreach ($productions as $production): ?>

                <tr>

                    <td data-label="Fecha">
                        <?php echo htmlspecialchars($production['production_date']); ?>
                    </td>

                    <td data-label="Producto">
                        <?php echo htmlspecialchars($production['product_name']); ?>
                    </td>

                    <td data-label="Procesado Por">
                        <?php echo htmlspecialchars($production['processed_by_name']); ?>
                    </td>

                    <td data-label="Registrado Por">
                        <?php echo htmlspecialchars($production['created_by_name']); ?>
                    </td>

                    <td data-label="Sección">
                        <?php echo htmlspecialchars($production['section_name']); ?>
                    </td>

                    <td data-label="Cantidad">
                        <?php echo htmlspecialchars($production['quantity']); ?>
                    </td>

                    <td data-label="Unidad">
                        <?php echo htmlspecialchars($production['unit']); ?>
                    </td>

                    <td class="action-links" data-label="Acciones">

                        <a href="view.php?id=<?php echo $production['id']; ?>">
                            Ver
                        </a>

                        <a href="edit.php?id=<?php echo $production['id']; ?>">
                            Editar
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

        <div class="pagination">

            <?php if ($page > 1): ?>
                <a href="<?php echo htmlspecialchars(buildPageUrl($page - 1)); ?>" class="btn btn-sm btn-secondary">
                    Anterior
                </a>
            <?php else: ?>
                <span class="btn btn-sm btn-secondary pagination-disabled">
                    Anterior
                </span>
            <?php endif; ?>

            <span class="pagination-status">
                Página <?php echo $page; ?> de <?php echo $totalPages; ?>
            </span>

            <?php if ($page < $totalPages): ?>
                <a href="<?php echo htmlspecialchars(buildPageUrl($page + 1)); ?>" class="btn btn-sm btn-secondary">
                    Siguiente
                </a>
            <?php else: ?>
                <span class="btn btn-sm btn-secondary pagination-disabled">
                    Siguiente
                </span>
            <?php endif; ?>

        </div>

        <?php endif; ?>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>

