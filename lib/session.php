<?php

require_once 'config.php';

session_start($config['session']);

function get_login_user($dbconn)
{
  $user = null;
  if (isset($_SESSION['user'])) {
    $user = getuserbyid($dbconn, $_SESSION['user']);
    if ($user === null) {
      unset($_SESSION['user']);
    }
  }

  return $user;
}

?>
