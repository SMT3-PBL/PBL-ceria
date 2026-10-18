<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

define('APP_RUNNING', true);
define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/config/database.php';
