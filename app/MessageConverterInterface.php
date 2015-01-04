<?php

interface MessageConverterInterface
{
    /**
     * @param $message string
     * @return mixed
     */
    public function in($message);

    /**
     * @param $data mixed
     * @return string
     */
    public function out($data);
}
