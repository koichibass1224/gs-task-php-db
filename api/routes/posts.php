<?php

namespace MyApp\Routes;

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
    var_dump($vars);
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
}
