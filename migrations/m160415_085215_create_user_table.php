<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user_table`.
 */
class m160415_085215_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->execute(file_get_contents(__DIR__ . '/create_user_table.sql'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
