<?php

namespace MyApp\Auth;

require_once __DIR__ . '/../config.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\ExpiredException;
use \Exception;

/**
 * Verify Password
 * @return Boolean
 * @param: $password: Strings - from Form
 * @param: $hasPassword: String - db password
 * cf.
 * https://www.php.net/manual/ja/function.password-verify.php
 * https://qiita.com/mpyw/items/f53e66b64cdab6f0aa54
 */
function verify_password($password, $hashPassword)
{
  $dummyHash = '$2y$10$p3v4KlSFqx11/84YF2xTJu';
  return password_verify(
    $password,
    isset($hashPassword)
      ? $hashPassword
      : $dummyHash
  );
}

/**
 * Create JWT Token
 * @return token: String
 * @param $data: Array
 * cf. https://github.com/firebase/php-jwt
 */
function create_jwt_token($data = [])
{
  $time = time();
  $payload = [
    'iss' => JWT_ISS,
    'aud' => JWT_AUD,
    // 現在時刻 + JWT_EXP を有効期限とする
    'exp' => $time + JWT_EXP,
    // 発行時間 含めることで token のハッシュを複雑にする
    'iat' => $time,
  ];

  // $data の情報を優先する ※ 演算子での配列結合は先にした配列のキーが優先される
  $payload = $data + $payload;

  $jwt = JWT::encode($payload, JWT_PRIVATE_KEY, JWT_HASH_ALG);

  return $jwt;
}

/**
 * Verify JWT token
 * Header に AUTHORIZATION で渡したデータは $_SERVER['HTTP_AUTHORIZATION'] で取得できる
 */
function verify_jwt_token()
{
  try {
    $auth = isset($_SERVER[JWT_HEADER]) ? $_SERVER[JWT_HEADER] : '';
    $token = get_authorization($auth);
    $payload = decorde_jwt_token($token);
    return $payload;
  } catch (Exception $e) {
    throw new Exception('VERIFY TOKEN ERROR: ' . $e->getMessage());
  }
}

/**
 * Decorde JWT Token
 * @return $data: Array
 * @param $token: String
 */
function decorde_jwt_token($token = null)
{
  try {
    $payload = JWT::decode($token, JWT_PRIVATE_KEY, array(JWT_HASH_ALG));
    return $payload;
  } catch (Exception $e) {
    throw new Exception('DECORDE TOKEN ERROR: ' . $e->getMessage());
  }
}

/**
 * Get Authorization by header
 * HEADER
 *   Authorization: Bearer token....
 * @param $auth: String $_SERVER['HTTP_AUTHORIZATION']
 */
function get_authorization($auth = '')
{
  if (!empty($auth) && preg_match('#\ABearer\s+(.+)\z#', $auth, $match)) {
    $token = $match[1];
    return $token;
  }
  throw new Exception('AUTHORIZATION ERROR');
}
