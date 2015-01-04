<?php

class JsonMessageConverter implements MessageConverterInterface
{
    /**
     * {@inheritance}
     */
    public function in($message)
    {
        return json_decode($message, true);
    }

    /**
     * {@inheritance}
     */
    public function out($data)
    {
        return json_encode($data);
    }
}
