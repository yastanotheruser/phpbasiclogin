<?php

require_once 'vendor/php-jwt/JWT.php';
require_once 'vendor/php-jwt/SignatureInvalidException.php';
require_once 'vendor/php-jwt/ExpiredException.php';
require_once 'config.php';

use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\ExpiredException;

function make_token(int $uid): string
{
  global $config;
  return JWT::encode(
    [
      'user' => $uid,
      'iat' => time(),
      'exp' => time() + 24 * 60 * 60,
    ],
    $config['jwt']['key'],
  );
}

function read_token(string $token, bool &$ok)
{
  global $config;
  $ok = false;

  try {
    $ok = true;
    return JWT::decode($token, $config['jwt']['key'], ['HS256']);
  } catch (SignatureInvalidException | ExpiredException $e) {
    return null;
  }
}

?>
