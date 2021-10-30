<?php

require_once 'lib/error.php';

allow_methods(['POST']);

header('Location: .', true, 303);
clear_error();

if (isset($_COOKIE['token'])) {
  setcookie('token', false);
}

?>
