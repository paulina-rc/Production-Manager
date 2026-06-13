<?php

require_once dirname(__DIR__) . '/config/auth.php';
?>

<h1>Welcome <?php echo $_SESSION['user_name']; ?></h1>

<p>Role ID: <?php echo $_SESSION['role_id']; ?></p>