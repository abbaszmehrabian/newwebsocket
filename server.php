<?php
require __DIR__ . '/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use testvoiseserver\src\AudioUploadServer;
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new AudioUploadServer()
        )
    ),
    8080
);

$server->run();
