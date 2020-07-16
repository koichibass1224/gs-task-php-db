<?php

namespace MyApp\DataBase\Posts;

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/connect.php';
require_once __DIR__ . '/tags.php';
require_once __DIR__ . '/post_tag_relationship.php';

use MyApp\DB;
use MyApp\DataBase\Tags;
use MyApp\DataBase\Relationships;
use \PDO;
use \Exception;
use \PDOException;

/**
 * @param $data(Array): [[id: post id, ... tag_id: tag id, tag_name: tag name], ...]
 * @return Array: [[id: post id, ... tags: [[id: tag id, name: tag name], ...]], ...];
 */
function format_post_tag_data($data = [])
{
  $formatData = [];
  foreach ($data as $post) {
    $postID = $post['id'];
    $tagID = $post['tag_id'];
    $tagName = $post['tag_name'];
    unset($post['tag_id']);
    unset($post['tag_name']);
    if (empty($formatData[$postID])) {
      $formatData[$postID] = $post + ['tags' => []];
    }
    $formatData[$postID]['tags'][] = [
      'id'   => $tagID,
      'name' => $tagName,
    ];
  }
  // [postID: [data], ...] => [[data], [data], ...]
  return array_values($formatData);
}


/**
 * GET ALL USERS POST
 * @param $uid: INT User ID
 */
function get_all_users_posts($uid)
{
  global $_;
  try {
    $pdo = DB::connect();
    // GET USER
    $sql = "SELECT * FROM {$_(DB_TABLE_USERS)} WHERE id = :uid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":uid", $uid, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // un exists user error
    if (!$user) {
      throw new Exception('ERROR: Access denied');
    }

    // get post with tags
    try {
      $sql = "SELECT
        pt.*, tt.id as tag_id, tt.name as tag_name
      FROM {$_(DB_TABLE_USERS)} as ut
      INNER JOIN (
        SELECT * FROM {$_(DB_TABLE_POSTS)}
        WHERE uid = :uid
        ) as pt
        ON ut.id = pt.uid
      INNER JOIN {$_(DB_TABLE_POST_TAG_RELATION)} as rt
        ON pt.id = rt.pid
      INNER JOIN {$_(DB_TABLE_TAGS)} as tt
        ON tt.id = rt.tid";

      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(":uid", $uid, PDO::PARAM_INT);
      $stmt->execute();
      $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return format_post_tag_data($res);
    } catch (PDOException $e) {
      throw new Exception('ERROR: DB GET POSTS ALL - ' . $e->getMessage());
    }
    return;
  } catch (Exception $e) {
    throw $e;
  }
}


/**
 * GET POST with its TAGS by POST ID
 * @param $pid: Int Post ID
 */
function get_post_by_id($pid)
{
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
function create_post(array $payload)
{
  global $_;

  list('uid' => $uid, 'title' => $title, 'tags' => $tags) = $payload;
  try {
    // create tags
    $tagsList = Tags\create($tags);

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
    Relationships\create($pdo, $pid, $tagsList);

    $pdo->commit();

    return get_post_by_id($pid);
  } catch (Exception $e) {
    throw $e;
  }
}


/**
 * UPDATE POST remove OLD RELATIONSHIPS and CREATE NEW RERATIONSHIP
 * @param $pid: Int Post ID
 * @param $payload: Object
 */
function update_post($pid, $payload)
{
  global $_;

  list('uid' => $uid, 'title' => $title, 'tags' => $tags) = $payload;
  try {
    // create tags
    $tagsList = Tags\create($tags);

    $pdo = DB::connect();
    $pdo->beginTransaction();

    // check has record
    $sql = "SELECT count(id) FROM {$_(DB_TABLE_POSTS)} WHERE id = :pid AND uid = :uid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
    $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
    $stmt->execute();
    $count = intval($stmt->fetchColumn());
    // no record to update
    if (!$count) {
      throw new Exception('ERROR: Access denied');
    }

    $sql = "UPDATE {$_(DB_TABLE_POSTS)}
      SET title = :title
      WHERE id = :pid AND uid = :uid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
    $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->execute();
    // update rows count
    $count = $stmt->rowCount();

    // Delete old relationship records
    Relationships\delete($pdo, $pid);

    // Create new relationship records
    Relationships\create($pdo, $pid, $tagsList);

    $pdo->commit();

    return get_post_by_id($pid);
  } catch (Exception $e) {
    throw $e;
  }
}


/**
 * DELETE POST with its RELATIONSHIPS
 * @param $pid: Int Post ID
 * @return Int deleted records count
 */
function delete_post($pid)
{
  global $_;
  try {
    $pdo = DB::connect();
    $sql = "DELETE FROM {$_(DB_TABLE_POSTS)} WHERE id = :pid";
    $stmt = $pdo->prepare($sql);

    $pdo->beginTransaction();
    $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
    $stmt->execute();
    // delete colimn count
    $count = $stmt->rowCount();
    $pdo->commit();

    return $count;
  } catch (Exception $e) {
    throw $e;
  }
}
