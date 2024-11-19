<?php
declare(strict_types=1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/src/ProductModel.php';
require_once __DIR__ . '/src/config/Database.php';
require_once __DIR__ . '/src/ProductController.php';
require_once __DIR__ . '/src/config/EnvLoader.php';

loadEnv(__DIR__ . '/.env');

$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASS');

header("Content-type: application/json; charset=UTF-8");  

$parts = explode("/", $_SERVER["REQUEST_URI"]);
if ($parts[2] != "products") {
    http_response_code(404);
    exit;
}

$id = $parts[3] ?? null;

$database = new Database($host, $dbname, $user, $password);
$model = new ProductModel($database); 
$controller = new ProductController($model);
$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);

