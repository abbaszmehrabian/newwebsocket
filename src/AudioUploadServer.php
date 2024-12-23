<?php
namespace testvoiseserver\src;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class AudioUploadServer implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // دریافت داده‌های خام صوتی
        $audioData = $msg;

        // بررسی نوع داده‌ها و تبدیل به داده‌های باینری
        if (is_string($audioData)) {
            $audioData = base64_decode($audioData);
        }

        // ارسال داده‌های خام به کلاینت‌ها بلافاصله، به جز کلاینتی که آنها را ارسال کرده است
        foreach ($this->clients as $client) {
            if ($from->resourceId !== $client->resourceId) {
                $client->send($audioData);
                echo "Audio chunk sent from user {$from->resourceId} to user {$client->resourceId}\n";
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
