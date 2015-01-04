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

        return $model->update($message);
    }
}
