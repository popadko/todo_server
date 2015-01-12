<?php

class MessageController implements MessageControllerInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var MessageTransformInterface
     */
    protected $transformer;

    public function __construct(Illuminate\Database\Eloquent\Model $model, MessageTransformInterface $transformer)
    {
        $this->model = $model;

        $this->transformer = $transformer;
    }

    /**
     * @{inheritance}
     */
    public function getAll()
    {
        return $this->model->all()->transform(function ($model) {
            /**
             * @var $model \Illuminate\Database\Eloquent\Model
             */
            return $this->transformer->out($model->attributesToArray());
        });
    }

    /**
     * @{inheritance}
     */
    public function handle($message)
    {
        $message = $this->transformer->in($message);

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
}
