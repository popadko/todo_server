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
        $container['capsule']->getConnection()->statement('
            CREATE TABLE todos (
                id uuid PRIMARY KEY NOT NULL,
                title VARCHAR(100) NOT NULL,
                completed BOOLEAN NOT NULL DEFAULT FALSE,
                created_at TIMESTAMP NOT NULL,
                updated_at TIMESTAMP NOT NULL
            )
        ');
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
