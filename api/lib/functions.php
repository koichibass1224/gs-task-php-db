<?php
require_once __DIR__ . '/../config.php';
require_once LIB_DIR . '/helpers.php';

/**
 * JSON を返す
 * @param $data: Array
 * @param: $success: Boolean
 * @param: $status: Int - status code
 */
function return_json($data = [], $success = true, $status = 200)
{
  $data['success'] = $success;
  header('Content-Type: application/json; charset=utf-8', true, $status);
  echo json_encode($data, JSON_UNESCAPED_UNICODE);
  exit();
}
