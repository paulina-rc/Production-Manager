<?php
require_once __DIR__ . '/../config/permissions.php';
?>

<div class="sidebar">

    <div class="sidebar-logo">

        <h2>
            Agroindustrias
        </h2>

        <span>
            Production Manager
        </span>

    </div>

    <div class="sidebar-menu">

        <a href="../dashboard/index.php">
            Dashboard
        </a>

        <?php if (isAdmin()): ?>

            <a href="../products/list.php">
                Productos
            </a>

            <a href="../users/list.php">
                Usuarios
            </a>

            <a href="../sections/list.php">
                Secciones
            </a>

        <?php endif; ?>

        <a href="../productions/list.php">
            Producciones
        </a>

        <a href="../reports/monthly.php">
            Reportes
        </a>

    </div>

    <div class="sidebar-footer">

        <a href="../users/profile.php">
            Perfil
        </a>

        <a href="../auth/logout.php">
            Salir
        </a>

    </div>

</div>