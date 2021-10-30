<?php

const PG_CONNECTION_STRING = 'host=localhost dbname=side user=postgres password=postgres';

function make_pg_connection()
{
  return pg_connect(PG_CONNECTION_STRING);
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
