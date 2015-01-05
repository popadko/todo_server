<?php

class MessageTransformer implements MessageTransformInterface
{
    /**
     * {@inheritance}
     */
    public function in($message)
    {
        foreach ($message as $key => &$value) {
            $method = 'in' . studly_case($key) . 'Field';
            if (method_exists($this, $method)) {
                $value = call_user_func(array($this, $method), $value);
            }
        }

        return $message;
    }

    /**
     * {@inheritance}
     */
    public function out($message)
    {
        foreach ($message as $key => &$value) {
            $method = 'out' . studly_case($key) . 'Field';
            if (method_exists($this, $method)) {
                $value = call_user_func(array($this, $method), $value);
            }
        }

        return $message;
    }

    protected function inCreatedAtField($value)
    {
        return intval($value / 1000);
    }

    protected function inUpdatedAtField($value)
    {
        return intval($value / 1000);
    }

    protected function outCreatedAtField($value)
    {
        return $value->timestamp;
    }

    protected function outUpdatedAtField($value)
    {
        return $value->timestamp;
    }
}
