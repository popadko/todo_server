<?php

interface MessageControllerInterface
{
    /**
     * @return Iterator
     */
    public function getAll();

    /**
     * @param $message string
     * @return mixed
     */
    public function handle($message);
}
