<?php

use Phpmig\Migration\Migration;

class CreateTodoTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();
        $container['capsule']->schema()->create('todos', function($table)
        {
            $table->increments('id');
            $table->string('title', 100);
            $table->boolean('completed');
            $table->timestamps();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $container = $this->getContainer();
        $container['capsule']->schema()->drop('todos');
    }
}
