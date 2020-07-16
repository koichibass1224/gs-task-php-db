<?php

namespace MyApp\Routes;

require_once __DIR__ . '/../config.php';
require_once DB_DIR . '/posts.php';

use MyApp\DataBase\Posts as DB_Posts;
use \Exception;

class Posts
{
  static function get_all_posts($vars)
  {
    $userID = $vars['id'];
    var_dump($userID);
    // TODO: get all post by $userID;
    return;
  }

  public static function get_post($vars)
  {
    var_dump($vars);
    $postID = $vars['id'];
    // TODO: get post by $postID;
    return;
  }

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

  public static function delete_post($vars)
  {
    var_dump($vars);
    $postID = $vars['id'];
    // TODO: delete post by $postID;
    return;
  }

  // return Error
  private static function returnError($e) {
    $status = 400;
    $error = $e->getMessage();
    return_json(['error' => $error], false, $status);
  }
}
