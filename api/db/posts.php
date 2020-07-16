<?php

namespace MyApp\DataBase\Posts;

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/connect.php';
require_once __DIR__ . '/tags.php';

use MyApp\DB;
use MyApp\DataBase\Tags;
use \PDO;
use \Exception;
use \PDOException;

/**
 * GET POST with its TAGS by POST ID
 * @param $pid: Int Post ID
 */
function get_post_by_id($pid) {
  global $_;
  try {
    $pdo = DB::connect();
    // GET POST
    $sql = "SELECT * FROM {$_(DB_TABLE_POSTS)} WHERE id = :pid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":pid", $pid, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    // no data
    if (!$post) {
      return [];
    }

    $pid = $post['id'];

    // GET TAGS
    $tags = Tags\get_post_tags(intval($pid));

    return [$post + ['tags' => $tags]];

  } catch (Exception $e) {
    throw $e;
  }
}

/**
 * CREATE NEW POST and RELATIONSHIPS
 * @param $payload: Object
 */
function create_post(Array $payload) {
  global $_;

  list('uid' => $uid, 'title' => $title, 'tags' => $tags) = $payload;
  try {
    // create tags
    $tagsList = Tags\create_tags($tags);

    // create new post
    $pdo = DB::connect();
    $sql = "INSERT INTO {$_(DB_TABLE_POSTS)} (id, uid, title) VALUES (null, :uid, :title)";
    $stmt = $pdo->prepare($sql);

    $pdo->beginTransaction();
    $stmt->bindValue(":uid", $uid, PDO::PARAM_INT);
    $stmt->bindValue(":title", $title, PDO::PARAM_STR);
    $stmt->execute();

    // Get last inserted post id;
    $pid = $pdo->lastInsertId('id');

    // Create Relationship
    $tagIds = [];
    $tags = [];
    $placeholder = [];
    foreach($tagsList as $key => $tag) {
      $placeholder[] = "(:pid, :tid{$key})";
      $tagIds[] = $key;
      $tags[] = $tag;
    }
    $sql = "INSERT INTO {$_(DB_TABLE_POST_TAG_RELATION)} (pid, tid) VALUES " . implode($placeholder, ',');
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":pid", $pid, PDO::PARAM_INT);
    foreach($tagIds as $tid) {
      $stmt->bindValue(":tid{$tid}", $tid, PDO::PARAM_INT);
    }
    $stmt->execute();

    $pdo->commit();

    return [
      'id'    => $pid,
      'title' => $title,
      'tags'  => $tags,
    ];
  } catch (Exception $e) {
    throw $e;
  }
}

