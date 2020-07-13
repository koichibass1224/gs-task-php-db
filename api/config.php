<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
// .env の内容は $_EMV で取得できる
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('TMP_DIR', __DIR__ . '/tmp');
define('DB_DIR', __DIR__ . '/db');

// database
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');

define('DB_NAME', 'todo');
define('DB_TABLE_USERS', 'users');
define('DB_TABLE_POSTS', 'posts');
define('DB_TABLE_TAGS', 'tags');
define('DB_TABLE_POST_TAG_RELATION', 'post_tag_relationships');
