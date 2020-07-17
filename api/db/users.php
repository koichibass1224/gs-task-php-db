<?php

namespace MyApp\DataBase\Users;

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/connect.php';
require_once LIB_DIR . '/helpers.php';

use MyApp\DB;
use \PDO;
use \Exception;
use \PDOException;

/**
 * users TABLE (DB_TABLE_USERS)
 * id:       BIGINT(20) AUTO INCREMENT
 * uid       VARCHAR(120) // TODO: firebase ID
 * name:     VARCHAR(120)
 * email:    VARCHAR(120)
 * password: VARCHAR(255)
 * status    INT DEFAULT 1
 */

/**
 * CREATE NEW USER
 */
function create_user($username, $email, $password)
{
  global $_;
  try {
    $pdo = DB::connect();
    $sql = "INSERT INTO {$_(DB_TABLE_USERS)} (id, name, email, password) VALUES (null, :name, :email, :password)";
    $stmt = $pdo->prepare($sql);
    // トランザクション開始
    $pdo->beginTransaction();
    try {
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      $stmt->bindValue(':name', $username, PDO::PARAM_STR);
      $stmt->bindValue(':email', $email, PDO::PARAM_STR);
      $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
      $stmt->execute();

      // INSERTされたデータのIDを取得
      $id = $pdo->lastInsertId('id');

      // トランザクション完了
      $pdo->commit();

      return [
        'id'   => $id,
        'name' => $username,
      ];
    } catch (PDOException $e) {
      throw new Exception('ERROR: CREATE USER - ' . $e->getMessage());
    }
  } catch (Exception $e) {
    throw $e;
  }
}


/**
 * @param id: String
 */
function get_user_by_id($id)
{
  global $_;
  try {
    $pdo = DB::connect();
    $sql = "SELECT * FROM {$_(DB_TABLE_USERS)} WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $pdo->beginTransaction();
    try {
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      $pdo->commit();

      return $data;
    } catch (PDOException $e) {
      throw new Exception('ERROR: GET USER BY ID - ' . $e->getMessage());
    }
  } catch (Exception $e) {
    throw $e;
  }
}


/**
 * email からユーザーを取得する
 * @return [ user data ]
 */
function get_user_by_email($email)
{
  global $_;
  try {
    $pdo = DB::connect();
    $sql = "SELECT * FROM {$_(DB_TABLE_USERS)} WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $pdo->beginTransaction();
    try {
      $stmt->bindValue(':email', $email, PDO::PARAM_STR);
      $stmt->execute();
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      $pdo->commit();

      return $data;
    } catch (PDOException $e) {
      throw new Exception('GET USER BY EMAIL ERROR: ' . $e->getMessage());
    }
  } catch (Exception $e) {
    throw $e;
  }
}


/**
 * username からユーザーを取得する
 * @return [ user data ]
 */
function get_user_by_name($username)
{
  global $_;
  try {
    $pdo = DB::connect();
    $sql = "SELECT * FROM {$_(DB_TABLE_USERS)} WHERE name = :username";
    $stmt = $pdo->prepare($sql);
    $pdo->beginTransaction();
    try {
      $stmt->bindValue(':username', $username, PDO::PARAM_STR);
      $stmt->execute();
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      $pdo->commit();

      return $data;
    } catch (PDOException $e) {
      throw new Exception('GET USER BY EMAIL ERROR: ' . $e->getMessage());
    }
  } catch (Exception $e) {
    throw $e;
  }
}
