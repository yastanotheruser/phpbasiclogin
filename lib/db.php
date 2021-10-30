<?php

const DB_DEFAULTS = [
  'host' => 'localhost',
  'port' => '5432',
  'dbname' => 'postgres',
  'user' => 'postgres',
  'password' => 'postgres',
];

function make_pg_connection()
{
  if (!($_config = parse_ini_file('db.ini'))) {
    $_config = [];
  }

  $config = [];
  foreach (array_keys(DB_DEFAULTS) as $param) {
    $v = array_key_exists($param, $_config)
      ? $_config[$param]
      : DB_DEFAULTS[$param];
    $v = str_replace('\\', '\\\\', $v);
    $v = str_replace(' ', '\\ ', $v);
    $config[$param] = $v;
  }

  return pg_connect(
    sprintf(
      'host=%s port=%s dbname=%s user=%s password=%s',
      $config['host'],
      $config['port'],
      $config['dbname'],
      $config['user'],
      $config['password']
    )
  );
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
