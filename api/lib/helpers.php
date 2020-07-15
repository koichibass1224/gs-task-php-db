<?php
require_once __DIR__ . '/../config.php';

function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// ISO8601 形式の日付を返す
function get_timestamp()
{
  $timestamp = new DateTime();
  return $timestamp->format(DateTime::ATOM);
}

// 定数を文字列展開するための helper 関数
// 関数内で使用する場合は global $_; で使えるようにする必要がある
$_ = function ($s) {
  return $s;
};
