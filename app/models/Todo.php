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
}
