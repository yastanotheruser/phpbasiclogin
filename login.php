<?php

require_once 'lib/error.php';
require_once 'lib/db.php';
require_once 'lib/session.php';

allow_methods(['POST']);

header('Location: .', true, 303);
clear_error();

$login = isset($_POST['login']) ? trim($_POST['login']) : null;
if (!$login) {
  return set_error('LOGIN_MISSING');
}

$password = isset($_POST['password']) ? trim($_POST['password']) : null;
if (!$password) {
  return set_error('PASSWORD_MISSING');
}

if (!($dbconn = make_pg_connection())) {
  return set_error('DATABASE_CONNECTION');
}

if (($user = getuserbylogin($dbconn, $login)) === null) {
  if (pg_last_error()) {
    return set_error('DATABASE_QUERY');
  }

  return set_error('BAD_LOGIN');
}

if (!password_verify($password, $user['password'])) {
  return set_error('BAD_LOGIN');
}

$_SESSION['user'] = $user['id'];

?>
