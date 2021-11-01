<?php

require_once 'config.php';

function make_pg_connection()
{
  $conn_string_params = array_map(
    function (string $p) {
      global $config;
      $v = $config['postgres'][$p];
      $v = str_replace('\\', '\\\\', $v);
      $v = str_replace(' ', '\\ ', $v);
      return "$p=$v";
    },
    ['host', 'port', 'dbname', 'user', 'password']
  );

  return pg_connect(implode(' ', $conn_string_params));
}

function getuserbylogin($dbconn, string $login)
{
  if (
    !pg_prepare(
      $dbconn,
      'getuserbylogin',
      'SELECT * FROM users WHERE login = $1'
    ) ||
    !($result = pg_execute($dbconn, 'getuserbylogin', [$login])) ||
    !($arr = pg_fetch_all($result))
  ) {
    return null;
  }

  return $arr[0];
}

function getuserbyid($dbconn, int $id)
{
  if (
    !pg_prepare($dbconn, 'getuserbyid', 'SELECT * FROM users WHERE id = $1') ||
    !($result = pg_execute($dbconn, 'getuserbyid', [$id])) ||
    !($arr = pg_fetch_all($result))
  ) {
    return null;
  }

  return $arr[0];
}

?>
