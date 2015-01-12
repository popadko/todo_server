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

    public function getCreatedAtAttribute()
    {
        return (new DateTime($this->attributes['created_at']))->getTimestamp();
    }

    public function getUpdatedAtAttribute()
    {
        return (new DateTime($this->attributes['updated_at']))->getTimestamp();
    }
}
