<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$connection = [];
if ($database_url = getenv('DATABASE_URL')) {
    $connection = parse_url($database_url);
    $aliases = [
        'user' => 'username',
        'pass' => 'password',
        'path' => 'database',
    ];

    foreach (array_only($connection, array_keys($aliases)) as $key => $value) {
        $connection[$aliases[$key]] = $value;
        unset($connection[$key]);
    }

    if (isset($connection['database'])) {
        $connection['database'] = substr($connection['database'], 1);
    }
}

$capsule->addConnection(array_merge([
    'driver'    => 'pgsql',
    'host'      => 'localhost',
    'database'  => 'test',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
    'port'      => '5432',
    'schema'    => 'public'
], $connection));

$capsule->setAsGlobal();

$capsule->bootEloquent();
