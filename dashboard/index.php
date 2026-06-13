<?php

require_once dirname(__DIR__) . '/config/auth.php';

?>

<h1>Production Manager</h1>

<p>Welcome <?php echo $_SESSION['user_name']; ?></p>

<p>Role ID: <?php echo $_SESSION['role_id']; ?></p>

<hr>

<ul>
    <li><a href="../dashboard/index.php">Dashboard</a></li>
    <li><a href="../products/list.php">Products</a></li>
    <li><a href="../productions/list.php">Productions</a></li>
    <li><a href="../reports/monthly.php">Reports</a></li>
    <li><a href="../users/list.php">Users</a></li>
    <li><a href="../auth/logout.php">Logout</a></li>
    <li><a href="../sections/list.php">Sections</a></li>
</ul>