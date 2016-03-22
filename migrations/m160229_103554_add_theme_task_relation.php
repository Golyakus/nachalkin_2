<?php

use yii\db\Migration;

class m160229_103554_add_theme_task_relation extends Migration
{
    public function up()
    {
		$this->addForeignKey('FK_theme_task', 'task', 'theme_id', 'theme','id');
    }

    public function down()
    {
        $this->dropForeignKey('FK_theme_task', 'task');
        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
