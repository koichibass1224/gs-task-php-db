<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
// .env の内容は $_EMV で取得できる
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('TMP_DIR', __DIR__ . '/tmp');
define('DB_DIR', __DIR__ . '/db');
define('LIB_DIR', __DIR__ . '/lib');
define('ROUTES_DIR', __DIR__ . '/routes');

// database
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');

define('DB_NAME', 'todo');
define('DB_TABLE_USERS', 'users');
define('DB_TABLE_POSTS', 'posts');
define('DB_TABLE_TAGS', 'tags');
define('DB_TABLE_POST_TAG_RELATION', 'post_tag_relationships');

// JWT
define('JWT_HEADER', 'HTTP_' . $_ENV['JWT_HEADER']);

define('JWT_PRIVATE_KEY', $_ENV['JWT_PRIVATE_KEY']);
define('JWT_HASH_ALG', $_ENV['JWT_HASH_ALG']);
// iss 発行者の識別子
define('JWT_ISS', $_ENV['JWT_ISS']);
// aud このTokenを利用することが想定される対象の識別子
define('JWT_AUD', $_ENV['JWT_AUD']);
// exp JWTの有効期限: 秒
define('JWT_EXP', $_ENV['JWT_EXP']);

// JWT refresh token
define('JWT_REF_PRIVATE_KEY', $_ENV['JWT_REF_REF_EXP']);
// exp
define('JWT_REF_EXP', $_ENV['JWT_REF_EXP']);
