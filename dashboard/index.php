<?php

require_once dirname(__DIR__) . '/config/auth.php';
require_once dirname(__DIR__) . '/config/database.php';

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
?>

<!DOCTYPE html>

<html lang="es">
<head>

<meta charset="UTF-8">

<title>Dashboard</title>

<link rel="stylesheet"
      href="../assets/css/style.css">

</head>
<body>

<?php require_once '../includes/sidebar.php'; ?>

<div class="main-content">

```
<div class="welcome-box">

    <h1>
        Bienvenida, <?php echo $_SESSION['user_name']; ?>
    </h1>

    <p>
        Sistema de Gestión de Producción Agroindustria
    </p>

</div>

<div class="stats-container">

    <div class="stat-card">
        <h3>📦 Productos</h3>
        <div class="stat-number">
    <?php echo $totalProducts; ?>
</div>
    </div>

    <div class="stat-card">
        <h3>👥 Usuarios</h3>
        <div class="stat-number">
    <?php echo $totalUsers; ?>
</div>
    </div>

    <div class="stat-card">
        <h3>🏫 Secciones</h3>
        <div class="stat-number">
    <?php echo $totalSections; ?>
</div>
    </div>

    <div class="stat-card">
        <h3>🧪 Producciones</h3>
        <div class="stat-number">
    <?php echo $totalProductions; ?>
</div>
    </div>

</div>

<div class="card-container">

    <div class="card">

        <h3>📦 Productos</h3>

        <p>
            Administrar productos del sistema.
        </p>

        <a class="btn"
           href="../products/list.php">
           Abrir
        </a>

    </div>

    <div class="card">

        <h3>👥 Usuarios</h3>

        <p>
            Administrar usuarios y permisos.
        </p>

        <a class="btn"
           href="../users/list.php">
           Abrir
        </a>

    </div>

    <div class="card">

        <h3>🏫 Secciones</h3>

        <p>
            Administrar secciones académicas.
        </p>

        <a class="btn"
           href="../sections/list.php">
           Abrir
        </a>

    </div>

    <div class="card">

        <h3>🧪 Producciones</h3>

        <p>
            Registrar y consultar producciones.
        </p>

        <a class="btn"
           href="../productions/list.php">
           Abrir
        </a>

    </div>

    <div class="card">

        <h3>📈 Reportes</h3>

        <p>
            Generar reportes mensuales, semestrales y anuales.
        </p>

        <a class="btn"
           href="../reports/monthly.php">
           Abrir
        </a>

    </div>

</div>
```

</div>

</body>
</html>
