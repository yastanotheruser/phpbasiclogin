<?php

$CONFIG_DEFAULTS = [
  'postgres' => [
    'host' => 'localhost',
    'port' => '5432',
    'dbname' => 'postgres',
    'user' => 'postgres',
    'password' => 'postgres',
  ],
  'session' => [
    'save_handler' => 'files',
    'cookie_httponly' => 'true',
    'cookie_samesite' => 'Strict',
  ],
];

$config = parse_ini_file('config.ini', true);
if ($config) {
  $config = array_replace_recursive($CONFIG_DEFAULTS, $config);
} else {
  $config = $CONFIG_DEFAULTS;
}

?>
