<?php

class Todo extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = [
        'id',
        'title',
        'completed',
        'created_at',
        'updated_at',
    ];

    public $incrementing = false;

    public $timestamps = false;

    public function setCreatedAtAttribute($time)
    {
        $this->attributes['created_at'] = intval($time) / 1000;
    }

    public function setUpdatedAtAttribute($time)
    {
        $this->attributes['updated_at'] = intval($time) / 1000;
    }
}
