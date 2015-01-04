<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class MessageComponent implements MessageComponentInterface
{
    /**
     * @var SplObjectStorage
     */
    private $clients;

    /**
     * @var MessageConverterInterface
     */
    private $converter;

    /**
     * @var MessageControllerInterface
     */
    private $controller;

    public function __construct(MessageConverterInterface $converter, MessageControllerInterface $controller)
    {
        $this->clients = new \SplObjectStorage;

        $this->converter = $converter;

        $this->controller = $controller;
    }

    public function onOpen(ConnectionInterface $connection)
    {
        $this->clients->attach($connection);

        foreach ($this->controller->getAll() as $item) {
            $connection->send($this->converter->out($item));
        }
    }

    public function onMessage(ConnectionInterface $from, $message)
    {
        $this->controller->handle($this->converter->in($message));

        array_walk($this->clients, function ($client) use ($message) {
            /**
             * @var $client ConnectionInterface
             */
            $client->send($message);
        });
    }

    public function onClose(ConnectionInterface $connection)
    {
        $this->clients->detach($connection);
    }

    public function onError(ConnectionInterface $connection, \Exception $e)
    {
        $connection->close();
    }
}
