<?php
require_once __DIR__ . '/../config/permissions.php';
?>

<div class="sidebar">

    <div class="sidebar-logo">

        <div class="logo-icon">
            <i class="fas fa-seedling"></i>
        </div>

        <div>

            <h2>
                Agroindustrias
            </h2>

            <span>
                Production Manager
            </span>

        </div>

    </div>

    <div class="sidebar-menu">

        <a href="../dashboard/index.php">
            <i class="fas fa-house"></i>
            <span>Dashboard</span>
        </a>

        <?php if (isAdmin()): ?>

            <a href="../products/list.php">
                <i class="fas fa-box"></i>
                <span>Productos</span>
            </a>

            <a href="../users/list.php">
                <i class="fas fa-users"></i>
                <span>Usuarios</span>
            </a>

            <a href="../sections/list.php">
                <i class="fas fa-school"></i>
                <span>Secciones</span>
            </a>

        <?php endif; ?>

        <a href="../productions/list.php">
            <i class="fas fa-industry"></i>
            <span>Producciones</span>
        </a>

        <a href="../reports/monthly.php">
            <i class="fas fa-file-lines"></i>
            <span>Reportes</span>
        </a>

    </div>

    <div class="sidebar-divider"></div>

    <div class="sidebar-footer">

        <a href="../users/profile.php">
            <i class="fas fa-user"></i>
            <span>Perfil</span>
        </a>

        <a href="../auth/logout.php">
            <i class="fas fa-right-from-bracket"></i>
            <span>Salir</span>
        </a>

    </div>

</div>

