<?php

# phpmig.php

include_once 'connection.php';

use \Pimple,
    \Phpmig\Adapter\Illuminate\Database;

$container = new Pimple();

$container['capsule'] = $capsule;

$container['phpmig.adapter'] = $container->share(function () use ($container) {
    return new Database($container['capsule'], 'migrations');
});

$container['phpmig.migrations_path'] = function () {
    return __DIR__ . DIRECTORY_SEPARATOR . 'migrations';
};

return $container;
