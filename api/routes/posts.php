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
      // Mock user id
      $userID = 1;

      // params
      list('title' => $title, 'tags' => $tags) = self::get_params();

      // validation payload data
      $errors = self::validate_post_data($title, $tags);
      if (!empty($errors)) {
        self::send_validation_error($errors);
        return;
      }

      $res = DB_Posts\create_post([
        'uid'   => $userID,
        'title' => $title,
        'tags'  => $tags,
      ]);

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
  public static function update_post($vars)
  {
    $postID = $vars['id'];
    try {
      if (empty($postID)) {
        throw new Exception('ERROR: API UPDATE POST - POST ID UNDEFINDED');
      }
      // TODO: get user id via JWT
      // Mock user id
      $userID = 1;

      // params
      list('title' => $title, 'tags' => $tags) = self::get_params();

      // validation payload data
      $errors = self::validate_post_data($title, $tags);
      if (!empty($errors)) {
        self::send_validation_error($errors);
        return;
      }

      $res = DB_Posts\update_post($postID, [
        'uid'   => $userID,
        'title' => $title,
        'tags'  => $tags,
      ]);

      return_json(['data' => $res]);
    } catch (Exception $e) {
      self::returnError($e);
    }
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

  /**
   * Validation Post data
   */
  private static function validate_post_data($title, $tags)
  {
    $errors = [];

    if (empty($title)) {
      $errors['title'] = 'Title is required.';
    }

    foreach ($tags as $tag) {
      if (!preg_match('/^([\w\-]+)$/', $tag)) {
        $errors['tags'] = 'Contains bad characters. Tag can have alphabet, `-` and `_`.';
        break;
      }
    }

    return $errors;
  }

  // get json params
  private static function get_params()
  {
    $json = file_get_contents("php://input");
    $params =  json_decode($json, true);
    $title = trim($params['title']);
    $tags = $params['tags'] ? $params['tags'] : [];

    $tags = array_map(function ($tag) {
      return strip_tags(trim($tag));
    }, $tags);

    return [
      'title' => $title,
      'tags' => $tags,
    ];
  }

  // Send validation error.
  private static function send_validation_error($errors)
  {
    return_json([
      'message' => 'validation error',
      'errors' => $errors,
    ], false, 400);
  }

  // return Error
  private static function returnError($e)
  {
    $status = 400;
    $error = $e->getMessage();
    return_json(['error' => $error], false, $status);
  }
}
