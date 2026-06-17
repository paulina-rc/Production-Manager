<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica que exista una sesión activa.
 */
function requireLogin()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../auth/login.php');
        exit;
    }
}

/**
 * Verifica que el usuario sea administrador.
 */
function requireAdmin()
{
    requireLogin();

    if ($_SESSION['role_id'] != 1) {
        header('Location: ../dashboard/');
        exit;
    }
}

/**
 * Verifica que sea administrador o profesor.
 */
function requireProfessorOrAdmin()
{
    requireLogin();

    if (!in_array($_SESSION['role_id'], [1, 2])) {
        header('Location: ../dashboard/');
        exit;
    }
}

/**
 * Retorna true si es administrador.
 */
function isAdmin()
{
    return isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1;
}

/**
 * Retorna true si es profesor.
 */
function isProfessor()
{
    return isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2;
}