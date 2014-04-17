<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection(array(
    'driver'    => 'pgsql',
    'host'      => 'localhost',
    'database'  => 'test',
    'username'  => 'user',
    'password'  => 'password',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
    'port'      => '5432',
    'schema'    => 'public'
));

$capsule->setAsGlobal();

$capsule->bootEloquent();
