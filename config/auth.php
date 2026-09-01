<?php

require_once __DIR__ . '/session.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$currentScript = basename($_SERVER['SCRIPT_NAME']);

if (
    !empty($_SESSION['must_change_password']) &&
    !in_array($currentScript, ['change_password.php', 'logout.php'])
) {
    header('Location: ../auth/change_password.php');
    exit;
}