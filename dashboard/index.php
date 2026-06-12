<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

?>

<h1>Welcome <?php echo $_SESSION['user_name']; ?></h1>

<p>Role ID: <?php echo $_SESSION['role_id']; ?></p>