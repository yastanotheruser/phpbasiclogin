<?php

require_once 'lib/error.php';
require_once 'lib/session.php';

allow_methods(['POST']);

header('Location: .', true, 303);

session_destroy();

?>
