<?php

const STATUS_TEXT = [
  400 => 'Bad request',
  403 => 'Forbidden',
  500 => 'Internal server error',
];

const ERROR_MESSAGES = [
  'LOGIN_MISSING' => 'login parameter missing',
  'PASSWORD_MISSING' => 'password parameter missing',
  'DATABASE_CONNECTION' => 'Couldn\'t connect to database',
  'DATABASE_QUERY' => 'Error while querying database',
  'BAD_LOGIN' => 'Bad credentials',
];

const ERROR_FRIENDLY_MESSAGES = [
  'LOGIN_MISSING' => 'No se envió el nombre de usuario',
  'PASSWORD_MISSING' => 'No se envió la contraseña',
  'DATABASE_CONNECTION' => 'Error del servidor',
  'DATABASE_QUERY' => 'Error del servidor',
  'BAD_LOGIN' => 'Credenciales inválidas',
];

function http_die(int $status, string $error, $json = true)
{
  http_response_code($status);
  if ($json) {
    if (array_key_exists($error, ERROR_MESSAGES)) {
      $code = $error;
      $error = ERROR_MESSAGES[$error];
    } else {
      $code = null;
    }

    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode(['code' => $code, 'error' => $error]));
  } else {
    $status_error = array_key_exists($status, STATUS_TEXT)
      ? STATUS_TEXT[$status]
      : "HTTP status $status";
    die("<h1>$status_error</h1>$error");
  }
}

function allow_methods(array $methods)
{
  if (!in_array(strtoupper($_SERVER['REQUEST_METHOD']), $methods, true)) {
    http_die(
      400,
      'The requested method is not implemented for this resource.',
      false
    );
  }
}

function set_error(string $error)
{
  if (array_key_exists($error, ERROR_MESSAGES)) {
    $_SESSION['error_message'] = ERROR_MESSAGES[$error];
    $_SESSION['error_code'] = $error;
  } else {
    $_SESSION['error_message'] = $error;
  }
}

function get_friendly_error_message()
{
  if (isset($_SESSION['error_code'])) {
    $code = $_SESSION['error_code'];
    if (array_key_exists($code, ERROR_FRIENDLY_MESSAGES)) {
      return ERROR_FRIENDLY_MESSAGES[$code];
    }
  }

  if (!isset($error) && isset($_SESSION['error_message'])) {
    return $_SESSION['error_message'];
  }

  return null;
}

function clear_error()
{
  unset($_SESSION['error_message']);
  unset($_SESSION['error_code']);
}

?>
