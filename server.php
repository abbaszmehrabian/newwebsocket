<?php
require __DIR__ . '/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use YourNamespace\AudioUploadServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new AudioUploadServer()
        )
    ),
);

$server->run();
