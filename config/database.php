<?php

// Lee el .env local si existe (para XAMPP). En Render no habrá .env,
// así que esto simplemente devuelve un arreglo vacío sin error.
$env = @parse_ini_file(__DIR__ . '/../.env') ?: [];

// Prioridad: variables de entorno reales (Render) sobre el archivo .env (XAMPP local)
function getConfigValue($env, $key, $default = null) {
    $value = getenv($key);
    if ($value !== false) {
        return $value;
    }
    return $env[$key] ?? $default;
}

$dbHost = getConfigValue($env, 'DB_HOST', 'localhost');
$dbName = getConfigValue($env, 'DB_NAME', 'production_manager');
$dbUser = getConfigValue($env, 'DB_USER', 'root');
$dbPass = getConfigValue($env, 'DB_PASSWORD', '');
$dbPort = getConfigValue($env, 'DB_PORT', '3306');

try {
    $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];

    // Si existe el certificado CA (necesario para Aiven en producción/demo),
    // se activa la conexión SSL. Localmente en XAMPP este archivo no existe,
    // así que se ignora sin causar error.
    $caPath = __DIR__ . '/aiven-ca.pem';
    if (file_exists($caPath)) {
        $options[PDO::MYSQL_ATTR_SSL_CA] = $caPath;
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    }

    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);

} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}