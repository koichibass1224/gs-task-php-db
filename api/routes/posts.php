<?php

namespace MyApp\Routes;

require_once __DIR__ . '/../config.php';
require_once DB_DIR . '/posts.php';

use MyApp\DataBase\Posts as DB_Posts;
use \Exception;

class Posts
{
  /**
   *  @return data => [{
   *    id: post id,
   *    uid: user id,
   *    title: post title,
   *    status: post status,
   *    created_at,
   *    updated_at,
   *    tags: [{id: tag id, name: tag: tag name}, ...]
   *  }, ...]
   */
  public static function get_all_posts($vars)
  {
    $userID = $vars['id'];
    try {
      if (empty($userID)) {
        throw new Exception('ERROR: API GET POSTS ALL - USER ID UNDEFIMNED');
      }

      $res = DB_Posts\get_all_users_posts($userID);
      return_json(['data' => $res]);
    } catch (Exception $e) {
      self::returnError($e);
    }
    return;
  }

  /**
   *  @return data => [{
   *    id: post id,
   *    uid: user id,
   *    title: post title,
   *    status: post status,
   *    created_at,
   *    updated_at,
   *    tags: [{id: tag id, name: tag: tag name}, ...]
   *  }]
   */
  public static function get_post($vars)
  {
    $postID = $vars['id'];
    try {
      if (empty($postID)) {
        throw new Exception('ERROR: API GET POST - POST ID UNDEFINDED');
      }

      $res = DB_Posts\get_post_by_id($postID);
      return_json(['data' => $res]);
    } catch (Exception $e) {
      self::returnError($e);
    }
    return;
  }

  /**
   *  @return data => [{
   *    id: post id,
   *    uid: user id,
   *    title: post title,
   *    status: post status,
   *    created_at,
   *    updated_at,
   *    tags: [{id: tag id, name: tag: tag name}, ...]
   *  }]
   */
  public static function create_post($vars)
  {
    try {
      // TODO: get user id via JWT

      // TODO: varidation payload data
      $res = DB_Posts\create_post([
        'uid'   => 1, //$userID,
        'title' => 'FOO', //$title,
        'tags'  => ['tag-1', 'tag-2', 'tag-4'], //$tags, //Array
      ]);

      return_json(['data' => $res]);
    } catch (Exception $e) {
      self::returnError($e);
    }
    return;
  }

  public static function update_post($vars)
  {
    var_dump($vars);
    $postID = $vars['id'];
    // TODO: update post by $postID;
    return;
  }

  /**
   *  @return data => deleted post id;
   */
  public static function delete_post($vars)
  {
    $postID = $vars['id'];
    try {
      if (empty($postID)) {
        throw new Exception('ERROR: API DELETE POST - POST ID UNDEFINDED');
      }

      // TODO: get user id via JWT

      $res = DB_Posts\delete_post($postID);
      return_json(['data' => $res ? $postID : null]);
    } catch (Exception $e) {
      self::returnError($e);
    }
    return;
  }

  // return Error
  private static function returnError($e) {
    $status = 400;
    $error = $e->getMessage();
    return_json(['error' => $error], false, $status);
  }
}
