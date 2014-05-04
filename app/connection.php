<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$host     = 'localhost';
$database = 'test';
$username = 'root';
$password = '';
$port     = '5432';

if ($connection = getenv('DATABASE_URL')) {
    $connection = substr($connection, strpos($connection, '://') + 3);

    $url = explode('@', $connection);

    $credentials = explode(':', $url[0]);
    $username    = $credentials[0];
    $password    = $credentials[1];

    $serverDB = explode('/', $url[1]);

    $server = explode(':', $serverDB[0]);
    $host   = $server[0];
    $port   = $server[1];

    $database = $serverDB[1];
}

$capsule->addConnection(array(
    'driver'    => 'pgsql',
    'host'      => $host,
    'database'  => $database,
    'username'  => $username,
    'password'  => $password,
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
    'port'      => $port,
    'schema'    => 'public'
));

$capsule->setAsGlobal();

$capsule->bootEloquent();
