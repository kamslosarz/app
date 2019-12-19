<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
include "../vendor/autoload.php";

define('DEBUG_MODE', true);
define('APP_DIR', dirname(__DIR__));

use App\App;

$app = new App();
$app(include '../config/app.php');