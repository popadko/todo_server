<?php

interface MessageTransformInterface
{
    /**
     * @param $data array
     * @return array
     */
    public function in($data);

    /**
     * @param $data array
     * @return array
     */
    public function out($data);
}
