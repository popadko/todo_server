<?php

namespace Todo;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class MessageComponent implements MessageComponentInterface
{
    private $clients;

    public function __construct()
    {
        // Create a collection of clients
        $this->clients = new \SplObjectStorage;

        $this->log('Server has started');
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

//        $conn->send(json_encode(array()));

        $this->log("New connection! ({$conn->resourceId})");
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }

        $message = json_decode($msg);

        if (isset($message->type)) {
            switch ($message->type) {
                default:
                    break;
            }
        }

    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        $this->log("Connection {$conn->resourceId} has disconnected");
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}";

        $conn->close();
    }

    protected function log($m)
    {
        echo $m . "\n";
    }
}