<?php

namespace MyApp;

require_once(__DIR__ . '/../config.php');

use \PDO;
use \PDOException;

class DB
{
  public static function connect()
  {
    try {
      $dns = 'mysql:dbname=' . DB_NAME . ';host=db;charset=utf8;';
      $options = [
        // カラム型に合わない値がINSERTされようとしたときSQLエラーとする
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET SESSION sql_mode='TRADITIONAL'",
        // SQLエラー発生時にPDOExceptionをスローさせる
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      ];
      return new PDO($dns, DB_USER, DB_PASSWORD, $options);
    } catch (PDOException $e) {
      throw new PDOException('DB connect error: ' . $e->getMessage());
    }
  }
}
