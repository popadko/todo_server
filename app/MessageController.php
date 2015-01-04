<?php

class MessageController implements MessageControllerInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    public function __construct(Illuminate\Database\Eloquent\Model $model)
    {
        $this->model = $model;
    }

    /**
     * @{inheritance}
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * @{inheritance}
     */
    public function handle($message)
    {
        $message = $this->handleFields($message);

        /**
         * @var $model \Illuminate\Database\Eloquent\Model
         */
        $model = $this->model->find($message['id']);

        if (!$model) {
            $model = $this->model->newInstance();
        }

        if (!empty($message['deleted_at'])) {
            return $model->delete($message);
        }

        $model->fill($message);

        return $model->save($message);
    }

    protected function handleFields($message)
    {
        foreach ($message as $key => &$value) {
            $method = 'handle'.studly_case($key).'Field';
            if (method_exists($this, $method)) {
                $value = call_user_func(array($this, $method), $value);
            }
        }

        return $message;
    }

    protected function handleCreatedAtField($value)
    {
        return intval($value / 1000);
    }

    protected function handleUpdatedAtField($value)
    {
        return intval($value / 1000);
    }
}
