<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class MessageComponent implements MessageComponentInterface
{
    private $clients;

    public function __construct(Illuminate\Database\Eloquent\Model $model)
    {
        // Create a collection of clients
        $this->clients = new \SplObjectStorage;

        $this->model = $model;

        $this->log('Server has started');
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        $this->sendAll($conn);

        $conn->send(json_encode(array('type' => 'open')));

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
                $message->data = $this->create($message->data);
                $msg           = json_encode($message);
                break;
            case 'delete':
                $this->delete($message->data);
                break;
            case 'update':
                $message = $this->update($message->data);
                if ($message) {
                    $from->send(json_encode($message));
                    return;
                }
                break;
            default:
                return;
        }

        foreach ($this->clients as $client) {
            $client->send($msg);
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
        echo "An error has occurred: $e";

        $conn->close();
    }

    protected function sendAll($connSocket)
    {
        $models = $this->model->all();
        foreach ($models as $model) {
            $connSocket->send(json_encode(array(
                'type' => 'create',
                'data' => array(
                    'id'        => $model->getKey(),
                    'title'     => $model->title,
                    'completed' => $model->completed,
                    'create'    => $model->created_at->timestamp,
                    'update'    => $model->updated_at->timestamp,
                )
            )));
        }
    }

    protected function create($data)
    {
        $model = $this->model->create(array(
            'title'      => $data->title,
            'completed'  => $data->completed,
            'created_at' => intval($data->update / 1000),
            'updated_at' => intval($data->update / 1000),
        ));
        return array(
            'id'        => $model->getKey(),
            'title'     => $model->title,
            'completed' => $model->completed,
            'create'    => $model->created_at->timestamp,
            'update'    => $model->updated_at->timestamp,
        );
    }

    protected function update($data)
    {
        $model = $this->model->find($data->id);

        if ($model->updated_at->timestamp > intval($data->update/1000)) {
            $record = array(
                'id'        => $model->getKey(),
                'title'     => $model->title,
                'completed' => $model->completed,
                'create'    => $model->created_at->timestamp,
                'update'    => $model->updated_at->timestamp,
            );
        } else {
            $model->update(array(
                'title'      => $data->title,
                'completed'  => $data->completed,
                'updated_at' => intval($data->update / 1000),
            ));
            $record = false;
        }
        return $record;
    }

    protected function delete($data)
    {
        $model = $this->model->find($data->id);

        if (!empty($model)) {
            $model->delete();
        }
    }

    protected function log($m)
    {
        echo $m . "\n";
    }
}