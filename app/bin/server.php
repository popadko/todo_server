<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../connection.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new MessageComponent(new Todo())
        )
    ),
    8080
);

$server->run();
