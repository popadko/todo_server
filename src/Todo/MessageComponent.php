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

        $this->sendAll($conn);

        $this->log("New connection! ({$conn->resourceId})");
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $message = json_decode($msg);

        if (!isset($message->type)) {
            return;
        }

        switch ($message->type) {
            case 'create':
                $this->create($message->data);
                break;
            case 'delete':
                $this->delete($message->data);
                break;
            case 'update':
                $this->update($message->data);
                break;
            default:
                return;
        }

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
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

    protected function sendAll($connSocket)
    {
        try {
            $conn = $this->getConnection();

            $collection = $this->getCollection($conn);

            $cursor = $collection->find();

            foreach ($cursor as $obj) {
                $connSocket->send(json_encode(
                    array(
                        'type' => 'create',
                        'data' => array(
                            'id' => $obj['_id']->{'$id'},
                            'title' => $obj['title'],
                            'completed' => $obj['completed'],
                        )
                    )
                ));
            }

            $conn->close();
        } catch (\MongoConnectionException $e) {
            die('Error connecting to MongoDB server');
        } catch (\MongoException $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    protected function create($data)
    {
        try {
            $conn = $this->getConnection();

            $collection = $this->getCollection($conn);

            $collection->insert($data);

            $conn->close();
        } catch (\MongoConnectionException $e) {
            die('Error connecting to MongoDB server');
        } catch (\MongoException $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    protected function update($data)
    {
        try {
            $conn = $this->getConnection();

            $collection = $this->getCollection($conn);

            $criteria = array(
                '_id' => new \MongoId($data->id),
            );

            $doc = $collection->findOne($criteria);

            $data = (array)$data;

            unset($data['id']);

            $collection->save(array_merge($doc, $data));

            $conn->close();
        } catch (\MongoConnectionException $e) {
            die('Error connecting to MongoDB server');
        } catch (\MongoException $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    protected function delete($data)
    {
        try {
            $conn = $this->getConnection();

            $collection = $this->getCollection($conn);

            $criteria = array(
                '_id' => new \MongoId($data->id),
            );

            $collection->remove($criteria);

            $conn->close();
        } catch (\MongoConnectionException $e) {
            die('Error connecting to MongoDB server');
        } catch (\MongoException $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    protected function getConnection()
    {
        // open connection to MongoDB server
        return new \Mongo('localhost');
    }

    protected function getCollection($conn)
    {
        // access database
        $db = $conn->todo;

        // access collection
        return $db->todo;
    }

    protected function log($m)
    {
        echo $m . "\n";
    }
}