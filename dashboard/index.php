<?php

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/permissions.php';

$totalProducts = $pdo->query("
    SELECT COUNT(*)
    FROM products
")->fetchColumn();

$totalUsers = $pdo->query("
    SELECT COUNT(*)
    FROM users
")->fetchColumn();

$totalSections = $pdo->query("
    SELECT COUNT(*)
    FROM sections
")->fetchColumn();

$totalProductions = $pdo->query("
    SELECT COUNT(*)
    FROM productions
    WHERE deleted_at IS NULL
")->fetchColumn();

$stmt = $pdo->query("
    SELECT
        productions.production_date,
        products.name AS product_name,
        sections.name AS section_name,
        productions.quantity,
        productions.unit
    FROM productions
    INNER JOIN products
        ON productions.product_id = products.id
    INNER JOIN sections
        ON productions.section_id = sections.id
    WHERE productions.deleted_at IS NULL
    ORDER BY productions.production_date DESC
    LIMIT 5
");

$recentProductions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$chartStmt = $pdo->query("
    SELECT
        DATE(production_date) AS day,
        COUNT(*) AS total
    FROM productions
    WHERE production_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    AND deleted_at IS NULL
    GROUP BY DATE(production_date)
    ORDER BY day ASC
");

$chartData = $chartStmt->fetchAll(PDO::FETCH_ASSOC);

$days = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-{$i} days"));
    $days[$date] = 0;
}
foreach ($chartData as $row) {
    if (isset($days[$row['day']])) {
        $days[$row['day']] = (int) $row['total'];
    }
}

$chartLabels = json_encode(array_map(function ($d) {
    return date('d/m', strtotime($d));
}, array_keys($days)));

$chartValues = json_encode(array_values($days));

$today = date('d/m/Y');

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <title>Dashboard</title>

    <?php require_once '../includes/header.php'; ?>

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

    <div class="welcome-box">

        <h1>
            Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
        </h1>

        <div class="dashboard-date">
            <?php echo $today; ?>
        </div>

    </div>

    <div class="stats-container">

        <div class="stat-card">

            <div class="stat-icon stat-icon--green">
                <i class="fas fa-box"></i>
            </div>

            <div class="stat-info">
                <h3>Productos</h3>
                <div class="stat-number">
                    <?php echo $totalProducts; ?>
                </div>
            </div>

        </div>

        <div class="stat-card">

            <div class="stat-icon stat-icon--green-light">
                <i class="fas fa-users"></i>
            </div>

            <div class="stat-info">
                <h3>Usuarios</h3>
                <div class="stat-number">
                    <?php echo $totalUsers; ?>
                </div>
            </div>

        </div>

        <div class="stat-card">

            <div class="stat-icon stat-icon--green-mid">
                <i class="fas fa-school"></i>
            </div>

            <div class="stat-info">
                <h3>Secciones</h3>
                <div class="stat-number">
                    <?php echo $totalSections; ?>
                </div>
            </div>

        </div>

        <div class="stat-card">

            <div class="stat-icon stat-icon--green-dark">
                <i class="fas fa-industry"></i>
            </div>

            <div class="stat-info">
                <h3>Producciones</h3>
                <div class="stat-number">
                    <?php echo $totalProductions; ?>
                </div>
            </div>

        </div>

    </div>

    <div class="dashboard-grid">

        <div class="dashboard-card">

            <h2>
                Producciones Recientes
            </h2>

            <?php if (empty($recentProductions)): ?>

                <p>
                    No hay producciones registradas.
                </p>

            <?php else: ?>

                <table class="table">

                    <thead>

                        <tr>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Seccion</th>
                            <th>Cantidad</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php foreach ($recentProductions as $production): ?>

                        <tr>

                            <td>
                                <?php echo htmlspecialchars($production['production_date']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($production['product_name']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($production['section_name']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($production['quantity']); ?>
                                <?php echo htmlspecialchars($production['unit']); ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            <?php endif; ?>

        </div>

        <div class="dashboard-card">

            <h2>
                Ultimos 7 Dias
            </h2>

            <div class="chart-wrapper">
                <canvas id="productionChart"></canvas>
            </div>

        </div>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>

<script>

const ctx = document.getElementById('productionChart').getContext('2d');

const gradient = ctx.createLinearGradient(0, 0, 0, 260);
gradient.addColorStop(0, 'rgba(22, 163, 74, 0.35)');
gradient.addColorStop(1, 'rgba(22, 163, 74, 0.03)');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo $chartLabels; ?>,
        datasets: [{
            label: 'Producciones',
            data: <?php echo $chartValues; ?>,
            backgroundColor: gradient,
            borderColor: '#16a34a',
            borderWidth: 2,
            borderRadius: 10,
            borderSkipped: false,
            barPercentage: 0.55,
            categoryPercentage: 0.7
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#14532d',
                titleFont: { family: 'Inter', size: 13 },
                bodyFont: { family: 'Inter', size: 12 },
                padding: 10,
                cornerRadius: 8,
                displayColors: false,
                callbacks: {
                    label: function(context) {
                        const val = context.parsed.y;
                        return val === 1 ? '1 produccion' : val + ' producciones';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                suggestedMax: 5,
                ticks: {
                    stepSize: 1,
                    font: { family: 'Inter', size: 12 },
                    color: '#6b7280',
                    padding: 8
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.04)',
                    drawBorder: false
                },
                border: { display: false }
            },
            x: {
                ticks: {
                    font: { family: 'Inter', size: 12 },
                    color: '#6b7280',
                    padding: 6
                },
                grid: { display: false },
                border: { display: false }
            }
        }
    }
});

</script>

</body>
</html>
