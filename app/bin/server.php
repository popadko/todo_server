<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../connection.php';

var_dump(getenv('PORT'));
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new MessageComponent(new Todo())
        )
    ),
    getenv('PORT') ? : 8080
);

$server->run();
