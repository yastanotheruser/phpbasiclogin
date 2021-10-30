<?php

require_once 'vendor/php-jwt/JWT.php';
require_once 'vendor/php-jwt/SignatureInvalidException.php';
require_once 'vendor/php-jwt/ExpiredException.php';

use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\ExpiredException;

$JWT_KEY = hex2bin(
  '7dc48897c4eecb540205500d01bee75e1c51117ceb5e83f12302e44d3fa3ba5d'
);

function make_token(int $uid): string
{
  global $JWT_KEY;
  return JWT::encode(
    [
      'user' => $uid,
      'iat' => time(),
      'exp' => time() + 24 * 60 * 60,
    ],
    $JWT_KEY
  );
}

function read_token(string $token, bool &$ok)
{
  global $JWT_KEY;
  $ok = false;

  try {
    $ok = true;
    return JWT::decode($token, $JWT_KEY, ['HS256']);
  } catch (SignatureInvalidException | ExpiredException $e) {
    return null;
  }
}

?>
