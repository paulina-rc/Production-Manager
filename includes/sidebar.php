<?php
require_once __DIR__ . '/../config/permissions.php';

$roleLabels = [
    1 => 'Administrador',
    2 => 'Profesor',
    3 => 'Administración'
];

$currentRoleLabel = $roleLabels[$_SESSION['role_id']] ?? 'Usuario';

$nameParts = explode(' ', $_SESSION['user_name'] ?? '');
$initials = '';
foreach ($nameParts as $part) {
    if (!empty($part)) {
        $initials .= mb_strtoupper(mb_substr($part, 0, 1));
        if (mb_strlen($initials) >= 2) break;
    }
}
?>

<div class="sidebar">

    <div class="sidebar-logo">

        <div class="logo-icon">
            <img src="../assets/img/logo.png"
                 alt="Logo"
                 class="logo-img">
        </div>

        <div>

            <h2>
                Agroindustria
            </h2>

            <span>
                Gestor de Producciones
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

        <a href="../reports/index.php">
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

<div class="topbar">

    <div class="topbar-left">

        <button class="theme-toggle"
                onclick="toggleTheme()"
                title="Cambiar tema">
            <i class="fas fa-moon" id="themeIcon"></i>
        </button>

    </div>

    <div class="topbar-user">

        <div class="topbar-info">

            <span class="topbar-name">
                <?php echo htmlspecialchars($_SESSION['user_name']); ?>
            </span>

            <span class="topbar-role">
                <?php echo htmlspecialchars($currentRoleLabel); ?>
            </span>

        </div>

        <div class="topbar-avatar">
            <?php echo htmlspecialchars($initials); ?>
        </div>

    </div>

</div>
