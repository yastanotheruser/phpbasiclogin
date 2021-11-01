<?php

$CONFIG_DEFAULTS = [
  'postgres' => [
    'host' => 'localhost',
    'port' => '5432',
    'dbname' => 'postgres',
    'user' => 'postgres',
    'password' => 'postgres',
  ],
  'jwt' => [
    'key' => null,
    'hexkey' => 'deadbeef',
    'algorithm' => 'HS256',
  ],
];

$config = parse_ini_file('config.ini', true);
if ($config) {
  array_replace_recursive($CONFIG_DEFAULTS, $config);
} else {
  $config = $CONFIG_DEFAULTS;
}

if (!isset($config['jwt']['key'])) {
  $config['jwt'] = hex2bin($config['jwt']['hexkey']);
}

?>
