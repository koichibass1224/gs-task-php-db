<?php
require_once(__DIR__ . '/../config.php');
require_once(DB_DIR . '/connect.php');

use MyApp\DB as DB;

try {
  $pdo = DB::connect();
  var_dump($pdo);
} catch (Exception $e) {
  var_dump($e);
}

phpinfo();
