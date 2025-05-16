<?php
define('BASE_DIR', realpath(__DIR__ . '/../'));
define('APP_DIR', realpath(__DIR__));
require_once BASE_DIR . '/vendor/autoload.php';
require_once APP_DIR . '/helpers.php';
Dotenv\Dotenv::createImmutable(BASE_DIR)->load();
require_once APP_DIR . '/db.php';
require_once APP_DIR . '/input.php';
require_once APP_DIR . '/validation.php';

$db = new DB($config);
$input = new Input();
