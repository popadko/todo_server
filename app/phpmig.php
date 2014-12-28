<?php

# phpmig.php

require_once 'connection.php';

use \Phpmig\Adapter\Illuminate\Database;

$container = new \Illuminate\Container\Container();

$container['capsule'] = $capsule;

$container['phpmig.adapter'] = $container->share(function () use ($container) {
    return new Database($container['capsule'], 'migrations');
});

$container['phpmig.migrations_path'] = function () {
    return __DIR__ . '/migrations';
};

return $container;
