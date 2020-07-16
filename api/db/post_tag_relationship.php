<?php

namespace MyApp\Database\Relationships;

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/connect.php';

use MyApp\DB;
use \PDO;
use \PDOException;

/**
 * create relationship records
 * @param: $pdo(PDO Object)
 * @param: $pid(INT): post ID
 * @param: $tags(Array): []
 */
function create(&$pdo, $pid, $tags = [])
{
  global $_;

  // create no relationship
  if (empty($tags)) {
    return;
  }

  try {
    $tagIDs = [];
    $placeholder = [];
    foreach ($tags as $key => $tag) {
      $placeholder[] = "(:pid, :tid{$key})";
      $tagIDs[] = $key;
    }

    $sql = "INSERT INTO {$_(DB_TABLE_POST_TAG_RELATION)} (pid, tid) VALUES " . implode($placeholder, ',');
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
    foreach ($tagIDs as $tid) {
      $stmt->bindValue(":tid{$tid}", $tid, PDO::PARAM_INT);
    }
    $stmt->execute();

    return true;
  } catch (PDOException $e) {
    throw $e;
  }
}


/**
 * delete relationship records
 * @param: $pdo(PDO Object)
 * @param: $pid(INT): post ID
 */
function delete(&$pdo, $pid)
{
  global $_;
  try {
    $sql = "DELETE FROM {$_(DB_TABLE_POST_TAG_RELATION)} WHERE pid = :pid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
    $stmt->execute();
    // get delete rows
    $count = $stmt->rowCount();

    return $count;
  } catch (PDOException $e) {
    throw $e;
  }
}
