<?php

namespace MyApp\Database\Tags;

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/connect.php';

use MyApp\DB;
use \PDO;
use \Exception;
use \PDOException;

/**
 * GET TAGS DATA BY TAGS NAME
 * @param $tags(Array) ['tag_name', ...]
 * @param $toObject(Boolean) default false
 *
 * @return Array:
 *   $toObject true => [tag_id => [id => tag_id, name => tag_name], ...]
 *   $toObject false => [[id => tag_id, name => tag_name], ...]
 */
function get_tags_by_names(Array $tags = [], $toObject = false) {
  global $_;

  // no tags
  if (!count($tags)) {
    return [];
  };

  try {
    $pdo = DB::connect();
    $placeholder = substr(str_repeat(',?', count($tags)), 1);
    $sql = "SELECT id, tt.id, tt.name
      FROM {$_(DB_TABLE_TAGS)} AS tt
      WHERE name IN ({$placeholder})";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($tags);

    // exists tags list
    if ($toObject) {
      $res = $stmt->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_UNIQUE);
    } else {
      $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $res;

  } catch (PDOException $e) {
    throw new PDOException('ERROR: GET TAGS BY TAG NAME - '. $e->getMessage());
  }
}

