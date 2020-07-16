<?php

namespace MyApp\Database\Tags;

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/connect.php';

use MyApp\DB;
use \PDO;
use \Exception;
use \PDOException;

/**
 * GET POST TAGS
 * @param $pid(INT) post id
 * @param $toObject(Boolean) default false
 * @return Array:
 *   $toObject true => [tag_id => [id => tag_id, name => tag_name], ...]
 *   $toObject false => [[id => tag_id, name => tag_name], ...]
 */
function get_post_tags(INT $pid, $toObject = false)
{
  global $_;
  try {
    $pdo = DB::connect();
    $sql = "SELECT tt.id, tt.name
      FROM {$_(DB_TABLE_POSTS)} as tp
      INNER JOIN {$_(DB_TABLE_POST_TAG_RELATION)} as rt
        ON tp.id = rt.pid
      INNER JOIN {$_(DB_TABLE_TAGS)} as tt
        ON tt.id = rt.tid
      WHERE tp.id = :pid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":pid", $pid, PDO::PARAM_INT);
    $stmt->execute();

    // exists tags list
    if ($toObject) {
      $res = $stmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);
    } else {
      $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $res;
  } catch (PDOException $e) {
    throw new PDOException('ERROR: GET POST TAGS - ' . $e->getMessage());
  }
}


/**
 * GET TAGS DATA BY TAGS NAME
 * @param $tags(Array) ['tag_name', ...]
 * @param $toObject(Boolean) default false
 *
 * @return Array:
 *   $toObject true => [tag_id => [id => tag_id, name => tag_name], ...]
 *   $toObject false => [[id => tag_id, name => tag_name], ...]
 */
function get_tags_by_names(array $tags = [], $toObject = false)
{
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
      $res = $stmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);
    } else {
      $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $res;
  } catch (PDOException $e) {
    throw new PDOException('ERROR: GET TAGS BY TAG NAME - ' . $e->getMessage());
  }
}


/**
 * CREATE TAGS
 * @param $tags(Array) ['tag_name', ...]
 * @return Array: [tag_id => [id => tag_id, name => tag_name], ...]
 */
function create_tags(array $tags = [])
{
  global $_;

  // no tags
  if (!count($tags)) {
    return [];
  };

  try {
    $pdo = DB::connect();
    // Check tag exists
    $existsTags = get_tags_by_names($tags, true);

    $existsTagNames = [];
    foreach ($existsTags as $tagData) {
      $existsTagNames[] = $tagData['name'];
    }

    // 追加するタグリストを作成
    $addTags = array_diff($tags, $existsTagNames);

    if (count($addTags) === 0) {
      return $existsTags;
    }

    // Add new Tags;
    $placeholder = [];
    foreach ($addTags as $key => $tag) {
      $placeholder[] = "(null, :name{$key})";
    }
    $sql = "INSERT INTO
      {$_(DB_TABLE_TAGS)} (id, name)
      VALUES " . implode($placeholder, ',');

    $stmt = $pdo->prepare($sql);

    $pdo->beginTransaction();
    foreach ($addTags as $key => $tag) {
      $stmt->bindValue(":name{$key}", $tag, PDO::PARAM_STR);
    }
    $stmt->execute();
    $pdo->commit();

    // get tags data;
    $res = get_tags_by_names($tags, true);
    return $res;
  } catch (PDOException $e) {
    throw new PDOException('ERROR: CREATE TAGS - ' . $e->getMessage());
  }
}
